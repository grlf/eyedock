<?php

class Am_Grid_Action_ExportPdf extends Am_Grid_Action_Abstract
{

    protected $privilege = 'export';
    protected $type = self::HIDDEN;

    public function run()
    {
        $ds = $this->grid->getDataSource();

        $fn = tempnam(DATA_DIR, 'zip_');

        $zip = new ZipArchive();
        $zip->open($fn, ZipArchive::OVERWRITE);

        foreach ($ds->selectAllRecords() as $ip) {
            $pdf = new Am_Pdf_Invoice($ip);
            $zip->addFromString($pdf->getFileName(), $pdf->render());
        }

        $zip->close();

        register_shutdown_function(array($this, 'cleanup'), $fn);

        $helper = new Am_Controller_Action_Helper_SendFile();
        $helper->sendFile($fn, 'application/octet-stream',
            array(
                //'cache'=>array('max-age'=>3600),
                'filename' => sprintf('invoices-%s.zip', sqlDate('now')),
        ));
    }

    public function cleanup($fn)
    {
        unlink($fn);
    }

    public function setGrid(Am_Grid_Editable $grid)
    {
        parent::setGrid($grid);
        if ($this->hasPermissions()) {
            $grid->addCallback(Am_Grid_ReadOnly::CB_RENDER_TABLE, array($this, 'renderLink'));
        }
    }

    public function renderLink(& $out)
    {
        $out .= sprintf('<div style="float:right">&nbsp;&nbsp;<a class="link" href="%s" target="_top">' . ___('Download Invoices (.pdf)') . '</a></div>',
                $this->getUrl());
    }

}