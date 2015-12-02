<?php

/*
 *
 *     Author: Alex Scott
 *      Email: alex@cgi-central.net
 *        Web: http://www.cgi-central.net
 *    Details: Admin Info / PHP
 *    FileName $RCSfile$
 *    Release: 4.7.1 ($Revision$)
 *
 * Please direct bug reports,suggestions or feedback to the cgi-central forums.
 * http://www.cgi-central.net/forum/
 *
 * aMember PRO is a commercial software. Any distribution is strictly prohibited.
 *
 */

class AdminEmailTemplatesController extends Am_Controller
{

    public function checkAdminPermissions(Admin $admin)
    {
        return $admin->hasPermission(Am_Auth_Admin::PERM_EMAIL);
    }

    public function deleteAction()
    {
        $this->getDi()->emailTemplateTable->load($this->getRequest()->getParam('id'))->delete();
        if (!$this->getRequest()->isAjax())
        {
            $this->_redirect($this->getRequest()->getParam('b'), array('prependBase' => false));
        }
    }

    protected function getDefaultNotificationTemplate($name)
    {
        $txt = '';

        if ($name == 'pending_to_user') {
            $txt = <<<CUT
Dear %user.name_f% %user.name_l%,

Thank you for signup. Your payment status is PENDING.
Please complete payment as described.

   Your User ID: %user.login%

Your may log-on to your member pages at:
%root_url%/member
and check your subscription status.

Best Regards,
Site Team
CUT;
        }

        return array(
            'subject' => 'Pending Payment',
            'txt' => $txt,
            'format' => 'text'
        );
    }

    public function editPendingNotificationRuleAction()
    {
        $form = $this->createPendingNotificationRulesForm();
        $form->addHidden('id')
            ->setValue($this->getRequest()->getParam('id'));
        $tpl = $this->getDi()->emailTemplateTable->load($this->getParam('id'));
        if ($form->isSubmitted())
        {
            $form->setDataSources(array(
                $this->getRequest()
            ));
        }
        else
        {
            $form->setDataSources(array(
                new HTML_QuickForm2_DataSource_Array(
                    array(
                        'attachments' => $this->prepareAttachments($tpl->attachments, $isReverse = true),
                        'conditions' => $this->prepareAttachments($tpl->conditions, $isReverse = true),
                        'days' => $this->prepareAttachments($tpl->days, $isReverse = true),
                        '_admins' => $tpl->recipient_admins ? explode(',', $tpl->recipient_admins) : -1,
                    ) +
                    $tpl->toArray()
                )
            ));
        }

        if ($form->isSubmitted() && $form->validate())
        {
            $vars = $form->getValue();
            unset($vars['label']);
            if (!$vars['email_template_layout_id']) {
                $vars['email_template_layout_id'] = null;
            }
            $tpl->setForUpdate($vars);
            $tpl->conditions = $this->prepareAttachments($this->getParam('conditions'));
            $tpl->attachments = $this->prepareAttachments($this->getParam('attachments'));
            $days = $this->getParam('days');
            sort($days);
            $tpl->days = $this->prepareAttachments($days);
            $tpl->recipient_admins = implode(',', $vars['_admins']);
            $tpl->save();
            $el = new Am_Form_Element_PendingNotificationRules($tpl->name);
            $el->setLabel($this->getRequest()->getParam('label'));
            $this->ajaxResponse(array(
                'content' => (string) $el
            ));
        }
        else
        {
            echo $form;
        }
    }

    public function addPendingNotificationRuleAction()
    {
        $form = $this->createPendingNotificationRulesForm();
        if($el = $form->getElementById('_admins'))
        {
            $el->setValue(-1);
        }
        if ($form->isSubmitted())
        {
            $form->setDataSources(array(
                $this->getRequest()
            ));
        }
        else
        {
            $form->setDataSources(array(
                new HTML_QuickForm2_DataSource_Array($this->getDefaultNotificationTemplate($this->getRequest()->getParam('name')))
            ));
        }

        if ($form->isSubmitted() && $form->validate())
        {
            $vars = $form->getValue();
            unset($vars['label']);
            if (!$vars['email_template_layout_id']) {
                $vars['email_template_layout_id'] = null;
            }
            $tpl = $this->getDi()->emailTemplateRecord;

            $tpl->setForInsert($vars);
            $tpl->conditions = $this->prepareAttachments($this->getParam('conditions'));
            $tpl->attachments = $this->prepareAttachments($this->getParam('attachments'));
            $days = $this->getParam('days');
            sort($days);
            $tpl->days = $this->prepareAttachments($days);
            $tpl->recipient_admins = implode(',', $vars['_admins']);
            $tpl->save();
            $el = new Am_Form_Element_PendingNotificationRules($tpl->name);
            $el->setLabel($this->getParam('label'));
            $this->ajaxResponse(array(
                'content' => (string) $el
            ));
        }
        else
        {
            echo $form;
        }
    }

    protected function createPendingNotificationRulesForm()
    {
        $form = new Am_Form_Admin('EmailTemplate');

        $form->addElement(new Am_Form_Element_Html('info'))
            ->setLabel(___('Template'))
            ->setHtml(
                sprintf('<div><strong>%s</strong><br /><small>%s</small></div>', $this->escape($this->getParam('name')), $this->escape($this->getParam('label'))
                )
        );

        if ($options = $this->getDi()->emailTemplateLayoutTable->getOptions()) {
            $form->addSelect('email_template_layout_id')
                ->setLabel(___('Layout'))
                ->loadOptions(array(''=>___('No Layout')) + $options);
        }

        $tt = Am_Mail_TemplateTypes::getInstance()->find($this->getParam('name'));
        if($tt && !empty($tt['isAdmin']))
        {
            $op = array('-1' => Am_Controller::escape(sprintf('%s <%s>',
                Am_Di::getInstance()->config->get('site_title') . ' Admin', Am_Di::getInstance()->config->get('admin_email'))));
            foreach (Am_Di::getInstance()->adminTable->findBy() as $admin)
            {
               $op[$admin->pk()] = Am_Controller::escape(sprintf('%s <%s>', $admin->getName(), $admin->email));
            }
            $form->addMagicSelect('_admins', array('value' => 'default'))
                ->setLabel(___('Admin Recipients'))
                ->loadOptions($op)
                ->setId('_admins')
                ->addRule('required');

        } else
        {
            $form->addText('bcc', array('class' => 'el-wide', 'placeholder' => ___('Email Addresses Separated by Comma')))
                ->setLabel(___("BCC\n" .
                    "blind carbon copy allows the sender of a message to conceal the person entered in the Bcc field from the other recipients"))
                ->addRule('callback', ___('Please enter valid e-mail addresses'), array('Am_Validate', 'emails'));
        }
        
        $form->addElement('hidden', 'name');

        $form->addMagicSelect('days')
            ->setLabel(___('Days to Send'))
            ->loadOptions($this->getDayOptions());

        $form->addMagicSelect('conditions')
            ->setLabel(___('Optional Conditions (By Product)') . "\n" . ___('notification will be sent in case of one of selected products exits in invoice'))
            ->loadOptions($this->getConditionProductOptions());

        $form->addMagicSelect('conditions')
            ->setLabel(___('Optional Conditions (By Paysystem)') . "\n" . ___('notification will be sent in case of one of selected payment system was used for invoice'))
            ->loadOptions($this->getConditionPaysysOptions());

        $body = $form->addElement(new Am_Form_Element_MailEditor($this->getParam('name'), array('upload-prefix'=>'email-pending')));

        $form->addElement('hidden', 'label')
            ->setValue($this->getRequest()->getParam('label'));

        return $form;
    }

    public function editAction()
    {

        if (!$this->getParam('name'))
            throw new Am_Exception_InputError(___('Name of template is undefined'));

        $form = $this->createForm();
        $tpl = $this->getTpl($this->getParam('copy_from', null));

        if ($form->isSubmitted())
        {
            $form->setDataSources(array(
                $this->getRequest()
            ));
        }
        else
        {
            $form->setDataSources(array(
                new HTML_QuickForm2_DataSource_Array(
                    array(
                        'attachments' => $this->prepareAttachments($tpl->attachments, $isReverse = true),
                        'conditions' => $this->prepareAttachments($tpl->conditions, $isReverse = true),
                        'days' => $this->prepareAttachments($tpl->days, $isReverse = true),
                        '_admins' => $tpl->recipient_admins ? explode(',', $tpl->recipient_admins) : -1,
                    ) +
                    $tpl->toArray()
                )
            ));
        }

        if ($form->isSubmitted() && $form->validate())
        {
            $vars = $form->getValue();
            unset($vars['label']);
            if (!$vars['email_template_layout_id']) {
                $vars['email_template_layout_id'] = null;
            }
            $tpl->isLoaded() ? $tpl->setForUpdate($vars) : $tpl->setForInsert($vars);
            $tpl->conditions = $this->prepareAttachments($this->getParam('conditions'));
            $tpl->attachments = $this->prepareAttachments($this->getParam('attachments'));
            $tpl->recipient_admins = implode(',', isset($vars['_admins']) ? $vars['_admins'] : array());
            $tpl->save();
        }
        else
        {
            echo $this->createActionsForm($tpl)
            . "\n"
            . $form
            . "\n"
            . $this->getJs(!$tpl->isLoaded());
        }
    }

    protected function getDayOptions()
    {

        $options = array(
            '0' => ___('Immediately'),
            '1' => ___('Next Day')
        );
        for ($i = 2; $i <= 40; $i++)
            $options[$i] = $this->getNumberString($i) . ___(' day');

        return $options;
    }

    protected function getNumberString($i)
    {
        switch ($i)
        {
            case 2 :
                return $i . 'nd';
                break;
            case 3 :
                return $i . 'rd';
                break;
            default :
                return $i . 'th';
        }
    }

    protected function getConditionProductOptions()
    {
        $product_options = array();
        foreach (Am_Di::getInstance()->productTable->getOptions() as $id => $title)
        {
            $product_options['PRODUCT-' . $id] = ___('Product: ') . Am_Controller::escape(___($title));
        }

        $group_options = array();
        foreach (Am_Di::getInstance()->productCategoryTable->getAdminSelectOptions() as $id => $title)
        {
            $group_options['CATEGORY-' . $id] = ___('Product Category: ') . Am_Controller::escape(___($title));
        }

        $options = array(
            ___('Products') => $product_options
        );
        if (count($group_options))
        {
            $options[___('Product Categories')] = $group_options;
        }

        return $options;
    }

    protected function getConditionPaysysOptions()
    {
        $options = array();
        foreach ($this->getDi()->paysystemList->getAllPublic() as $ps)
        {
            $options['PAYSYSTEM-' . $ps->getId()] = Am_Controller::escape(___($ps->getTitle()));
        }

        return $options;
    }

    protected function getTpl($copy_from = null)
    {
        if ($copy_from)
            return $this->getCopiedTpl($copy_from);

        $tpl = $this->getDi()->emailTemplateTable->getExact(
                $this->getParam('name'), $this->getParam('lang', $this->getDefaultLang()), $this->getParam('day', null)
        );

        if (!$tpl)
        {
            $tpl = $this->getDi()->emailTemplateRecord;
            $tpl->name = $this->getParam('name');
            $tpl->lang = $this->getParam('lang', $this->getDefaultLang());
            $tpl->subject = $this->getParam('name');
            $tpl->day = $this->getParam('day', null);
            $tpl->format = 'text';
            $tpl->plain_txt = null;
            $tpl->txt = null;
            $tpl->attachments = null;
        }

        return $tpl;
    }

    protected function getCopiedTpl($copy_from)
    {
        $sourceTpl = $this->getDi()->emailTemplateTable->getExact(
                $this->getParam('name'), $copy_from
        );

        if (!$sourceTpl)
        {
            throw new Am_Exception_InputError(___('Trying to copy from unexisting template : %s', $copy_from));
        }

        $sourceTpl->lang = $this->getParam('lang', $this->getDefaultLang());

        return $sourceTpl;
    }

    protected function createForm()
    {
        $form = new Am_Form_Admin('EmailTemplate');

        $form->addElement(new Am_Form_Element_Html('info'))
            ->setLabel(___('Template'))
            ->setHtml(
                sprintf('<div><strong>%s</strong><br /><small>%s</small></div>', $this->escape($this->getParam('name')), $this->escape($this->getParam('label'))
                )
        );

        $form->addElement('hidden', 'name');

        $langOptions = $this->getLanguageOptions(
                $this->getDi()->config->get('lang.enabled',
                    array($this->getDi()->config->get('lang.default', 'en')))
        );
        /* @var $lang HTML_QuickForm2_Element */
        $lang = $form->addElement('select', 'lang')
                ->setId('lang')
                ->setLabel(___('Language'))
                ->loadOptions($langOptions);
        if (count($langOptions) == 1)
            $lang->toggleFrozen(true);
        $lang->addRule('required');

        if ($options = $this->getDi()->emailTemplateLayoutTable->getOptions()) {
            $form->addSelect('email_template_layout_id')
                ->setLabel(___('Layout'))
                ->loadOptions(array(''=>___('No Layout')) + $options);
        }

        $tt = Am_Mail_TemplateTypes::getInstance()->find($this->getParam('name'));
        if($tt && !empty($tt['isAdmin']))
        {
            $op = array('-1' => Am_Controller::escape(sprintf('%s <%s>',
                Am_Di::getInstance()->config->get('site_title') . ' Admin', Am_Di::getInstance()->config->get('admin_email'))));
            foreach (Am_Di::getInstance()->adminTable->findBy() as $admin)
            {
               $op[$admin->pk()] = Am_Controller::escape(sprintf('%s <%s>', $admin->getName(), $admin->email));
            }
            $form->addMagicSelect('_admins', array('value' => 'default'))
                ->setLabel(___('Admin Recipients'))
                ->loadOptions($op)
                ->addRule('required');

        } else
        {
            $form->addText('bcc', array('class' => 'el-wide', 'placeholder' => ___('Email Addresses Separated by Comma')))
                ->setLabel(___("BCC\n" .
                    "blind carbon copy allows the sender of a message to conceal the person entered in the Bcc field from the other recipients"))
                ->addRule('callback', ___('Please enter valid e-mail addresses'), array('Am_Validate', 'emails'));
        }

        $form->addScript()->setScript(<<<CUT
$("#checkbox-recipient-other").change(function(){
$("#row-input-recipient-emails").toggle(this.checked);
}).change();
CUT
        );


        $body = $form->addElement(new Am_Form_Element_MailEditor($this->getParam('name')));

        $form->addElement('hidden', 'label')
            ->setValue($this->getParam('label'));

        return $form;
    }

    protected function createActionsForm(EmailTemplate $tpl)
    {
        $form = new Am_Form_Admin('EmailTemplate_Actions');

        $form->addElement('hidden', 'name')
            ->setValue($tpl->name);

        $langOptions = $this->getLanguageOptions(
                $this->getDi()->emailTemplateTable->getLanguages(
                    $tpl->name, null, $tpl->lang
                )
        );

        if (count($langOptions))
        {
            $lang_from = $form->addElement('select', 'copy_from')
                    ->setId('another_lang')
                    ->setLabel(___('Copy from another language'))
                    ->loadOptions(array('0' => '--' . ___('Please choose') . ' --') + $langOptions)
                    ->setValue(0);
        }

        if (isset($tpl->lang) && $tpl->lang)
        {
            $form->addElement('hidden', 'lang')
                ->setValue($tpl->lang);
        }

        $form->addElement('hidden', 'label')
            ->setValue($this->getParam('label'));

        //we do not show action's form if there is not any avalable action
        if (!count($langOptions))
        {
            $form = null;
        }

        return $form;
    }

    protected function prepareAttachments($att, $isReverse = false)
    {
        if ($isReverse)
        {
            return (!($att == '' || is_null($att)) ? explode(',', $att) : array());
        }
        else
        {
            return (is_array($att) ? implode(',', $att) : null);
        }
    }

    protected function getLanguageOptions($languageCodes)
    {
        $languageNames = $this->getDi()->languagesListUser;
        $options = array();
        foreach ($languageCodes as $k)
        {
            list($k, ) = explode('_', $k);
            $options[$k] = "[$k] " . $languageNames[$k];
        }
        return $options;
    }

    protected function getDefaultLang()
    {
        list($k, ) = explode('_', $this->getDi()->app->getDefaultLocale());
        return $k;
    }

    protected function exportAction()
    {

        $this->_helper->sendFile->sendData(
            $this->getDi()->emailTemplateTable->exportReturnXml(array('email_template_id')), 'text/xml', 'amember-email-templates-' . $this->getDi()->sqlDate . '.xml');
    }

    function importAction()
    {
        $form = new Am_Form_Admin;

        $import = $form->addFile('import')
                ->setLabel(___('Upload file [email-templates.xml]'));

        $form->addStatic('')->setContent(___('WARNING! All existing e-mail templates will be removed from database!'));
        //$import->addRule('required', 'Please upload file');
        //$form->addAdvCheckbox('remove')->setLabel('Remove your existing templates?');
        $form->addSaveButton(___('Upload'));

        if ($form->isSubmitted() && $form->validate())
        {
            $value = $form->getValue();

            $fn = DATA_DIR . '/import.email-templates.xml';

            if (!move_uploaded_file($value['import']['tmp_name'], $fn))
                throw new Am_Exception_InternalError(___('Could not move uploaded file'));

            $xml = file_get_contents($fn);
            if (!$xml)
                throw new Am_Exception_InputError(___('Could not read XML'));

            $count = $this->getDi()->emailTemplateTable->deleteBy(array())->importXml($xml);
            $this->view->content = ___('Import Finished. %d templates imported.', $count);
        } else
        {
            $this->view->content = (string) $form;
        }
        $this->view->title = ___('Import E-Mail Templates from XML file');
        $this->view->display('admin/layout.phtml');
    }

    function getJs($showOffer = false)
    {

        $offerText = json_encode((nl2br(___("This email template is empty in given language.\n" .
                        "Press [Copy] to copy template from default language [English]\n" .
                        "Press [Skip] to type it manually from scratch."))));
        $copy = ___("Copy");
        $skip = ___("Skip");
        if ($showOffer)
        {
            $jsOffer = <<<CUT
var div = $('<div><div>');
div.append($offerText+"<br />")
$('body').append(div);
div.dialog({
        autoOpen: true,
        modal : true,
        title : "",
        width : 350,
        position : ['center', 'center'],
        buttons: {
            "$copy" : function() {
                $("#another_lang").val('en');
                $("#another_lang").closest('form').ajaxSubmit({
                    success : function(data) {
                        $('#email-template-popup').empty().append(data);
                    }
                });
                $(this).dialog("close");
            },
            "$skip" : function() {
                $(this).dialog("close");
            }
        },
        close : function() {
            div.remove();
        }
    });          
CUT;
        }
        else
        {
            $jsOffer = '';
        }


        return <<<CUT
<script type="text/javascript">   
(function($){
setTimeout(function(){
    $("#lang").change(function(){
        var importantVars = new Array(
            'lang', 'name', 'label'
        );
        $.each(this.form, function() {
            if ($.inArray(this.name, importantVars) == -1) {
                if (this.name == 'format') {
                    this.selectedIndex = null;
                } else {
                    this.value='';
                }
            }
        })
        $(this.form).ajaxSubmit({
                        success : function(data) {
                            $('#email-template-popup').empty().append(data);
                        }
                    });
    });

    $("#another_lang").change(function(){
        if (this.selectedIndex == 0) return;
        $(this.form).ajaxSubmit({
                        success : function(data) {
                            $('#email-template-popup').empty().append(data);
                        }
                    });
    });
    
    $jsOffer
}, 100);

})(jQuery)
</script>
CUT;
    }

}