<?php
/**
 * Class represents records from table product_upgrade
 * {autogenerated}
 * @property int $product_upgrade_id 
 * @property int $from_billing_plan_id 
 * @property int $to_billing_plan_id 
 * @property double $surcharge 
 * @see Am_Table
 */
class ProductUpgrade extends Am_Record 
{
    const TYPE_DEFAULT = 'default';
    const TYPE_FLAT = 'flat';
    /** @return BillingPlan */
    function getFromPlan()
    {
        return $this->getDi()->billingPlanTable->load($this->from_billing_plan_id, false);
    }
    /** @return BillingPlan */
    function getToPlan()
    {
        return $this->getDi()->billingPlanTable->load($this->to_billing_plan_id, false);
    }
    /** @return Product */
    function getFromProduct()
    {
        $pr = $this->getDi()->productTable->load($this->getFromPlan()->product_id);
        return $pr;
    }
    /** @return Product */
    function getToProduct()
    {
        $pr = $this->getDi()->productTable->load($this->getToPlan()->product_id);
        return $pr;
    }

    /**
     * Calculate first payment for upgraded/downgraded subscription
     * calculated move from $item -> $to billling plan
     * @return Invoice
     */
    function createUpgradeInvoice(Invoice $exInvoice, InvoiceItem $item)
    {
        $row = array(
            'user_id' => $exInvoice->user_id,
            'paysys_id' => $exInvoice->paysys_id,
            'currency' => $exInvoice->currency,
            'tax_type' => $exInvoice->tax_rate,
            'tax_rate' => $exInvoice->tax_type,
            'tax_title' => $exInvoice->tax_title,
            'is_confirmed' => 1,
        );
        $invoice = $this->getDi()->invoiceTable->createRecord($row);
        $to = $this->getToPlan();
        if (!$to)
            throw new Am_Exception_Configuration("Upgrade: cannot load [to] plan {$this->to_billing_plan_id}");
        $pr = $to->getProduct();
        if (!$pr)
            throw new Am_Exception_Configuration("Upgrade: cannot load [to] product {$to->product_id}");
        $invoice->add($pr, $item->qty);
        $newItem = $invoice->getItem(0);
        /* @var $newItem InvoiceItem */
        $invoice->data()->set(Invoice::UPGRADE_INVOICE_ID, $exInvoice->pk());
        $invoice->data()->set(Invoice::UPGRADE_INVOICE_ITEM_ID, $item->pk());
        $invoice->calculate();
        // now calculate discounts and surcharges

        $unusedAmount = $this->getUnusedAmount($exInvoice, $item);

        if($this->type == self::TYPE_FLAT)
        {
            $newItem->first_price = $this->surcharge;
        }
        else
        {
            $unusedAmount = $this->getUnusedAmount($exInvoice, $item);
            // magic upgrade formula!
            $newItem->first_price = moneyRound(
                $newItem->first_price
                - $unusedAmount 
                + $this->surcharge);
        }
            
        if ($newItem->first_price < 0)
        {
            $invoice->data()->set(Invoice::UPGRADE_REFUND, moneyRound($unusedAmount + $newItem->first_price));
            $newItem->first_price = 0.0;
        }
        
        $invoice->calculate();
        $this->getDi()->hook->call(Am_Event::BEFORE_PRODUCT_UPGRADE, array(
            'invoice' => $invoice,
            'exItem'    => $item,
            'exInvoice' => $exInvoice,
            'upgrade'   => $this,
        ));
        return $invoice;
    }
    
    
    function addProrateDays()
    {
        throw new Am_Exception_NotImplemented(__METHOD__);
        /* // prorate code
        if ($daysPaid)
        {
            $k = $this->compareDayCost($item, $newItem);
            $daysPaid = intval($daysPaid * $k); // recalculate to new billing plan
            $p = new Am_Period($newItem->first_period);
            $date = $p->addTo('now');
            $p = new Am_Period("{$daysPaid}d");
            $date = $p->addTo($date);
            $days_diff = intval((strtotime($date . ' 00:00:00') - strtotime('today 00:00:00')) / (3600*24));
            $newItem->first_period = "{$days_diff}d";
            $invoice->first_period = "{$days_diff}d";
        }*/
    }
    
    /**
     * Return unused amount for $item subscription
     * null will be returned if:
     *   - subscription is lifetime
     *   - subscription is for free product
     *   - subscription is expired
     * @param Invoice $invoice
     * @param InvoiceItem $item
     * @return float|null
     */
    function getUnusedAmount(Invoice $invoice, InvoiceItem $item)
    {
        $row = $this->getDi()->db->selectRow("
            SELECT begin_date, expire_date 
            FROM ?_access
            WHERE invoice_id=?d AND product_id=?
            ORDER by expire_date desc LIMIT 1 
            ",
                $invoice->pk(), $item->item_id);
        if (!$row) return;
        $maxExpire = $row['expire_date'];
        $maxBegin  = $row['begin_date'];
        
        if ($maxExpire < $this->getDi()->sqlDate)
            return null;
        if ($maxExpire == Am_Period::MAX_SQL_DATE)
            return null;
        
        $daysTotal  = $this->diffDays($maxBegin, $maxExpire);
        $daysUnused = $this->diffDays($this->getDi()->sqlDate, $maxExpire) - 1 ;// -1 as today date can be fully used
        
        $pc = $invoice->getPaymentsCount();
        $field = ($pc == 1 && (float)$invoice->first_total) || ($pc == 0)
            ? 'first_total' : 'second_total';
        $paid = $item->get($field);
        return moneyRound($daysUnused * $paid / $daysTotal);
    }
    
    function diffDays($date1, $date2)
    {
        return round((strtotime("$date2 12:00:00")  
                    - strtotime("$date1 12:00:00")) / 86400);
    }
    
    
    /**
     * @param InvoiceItem $old
     * @param InvoiceItem $new
     * @return float old_price / new_price
     */
    function compareDayCost(InvoiceItem $old, InvoiceItem $new)
    {
        // calculate difference in money
        // if both products have second_period we can compare prices
        $k = 1.0;
        foreach (array('second_', 'first_') as $k)
        {
            $period_o = $old->get($k.'period');
            $period_n = $new->get($k.'period');
            $price_o = (float)$old->get($k.'price');
            $price_n = (float)$new->get($k.'price');
            if (!$price_n || !$price_o || !$period_o || !$period_n) continue;
            if ($period_o == $period_n)
                return round($price_o / $price_n, 4);
            // else we need to recalculate both periods to days
            $po = new Am_Period($period_o);
            $pn = new Am_Period($period_n);
            $days_o = strtotime($po->addTo('2012-04-01') . ' 00:00:00')
                     - strtotime('2012-04-01 00:00:00');
            $days_o = intval($days_o/(3600*24));
            $days_n = strtotime($pn->addTo('2012-04-01') . ' 00:00:00')
                     - strtotime('2012-04-01 00:00:00');
            $days_n = intval($days_n/(3600*24));
            $price_o /= $days_o;
            $price_n /= $days_n;
            return round($price_o / $price_n, 4);
        }
    }
}

class ProductUpgradeTable extends Am_Table 
{
    protected $_key = 'product_upgrade_id';
    protected $_table = '?_product_upgrade';
    protected $_ucache = array();
    /**
     * @param Invoice $invoice
     * @return array of ProductUpgrade
     */
    function findUpgrades(Invoice $invoice, InvoiceItem $item)
    {
        if (empty($this->_ucache))
        {
            foreach ($this->_db->select("SELECT * FROM {$this->_table}") as $row)
                $this->_ucache[ $row['from_billing_plan_id'] ][] = $row;
        }
        $ret = array();
        
        if(!array_key_exists($item->billing_plan_id, $this->_ucache)) return $ret;
        
        foreach ($this->_ucache[ $item->billing_plan_id ] as & $row)
        {
            if (empty($row['_obj']))
                $row['_obj'] = $this->createRecord($row);
            if ($invoice->canUpgrade($item, $row['_obj']))
                $ret[] = $row['_obj'];
        }
        return $ret;
    }
}