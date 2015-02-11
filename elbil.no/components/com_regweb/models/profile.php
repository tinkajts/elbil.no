<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

class RegwebModelProfile extends JModelForm {

    protected $formData = null;

    public function getFieldsConfig()
    {
        static $fieldsConfig = null;
        if ($fieldsConfig == null) {
            $fieldsConfig = array(
                'membernumber' => array('type' => 'text'),
                'firstname' => array('type' => 'text'),
                'lastname' => array('type' => 'text'),
                'address1' => array('type' => 'text'),
                'address2' => array('type' => 'text'),
                'postalcode' => array('type' => 'text'),
                'phone1' => array('type' => 'text'),
                'phone2' => array('type' => 'text'),
                'mobile' => array('type' => 'text'),
                'email' => array('type' => 'text'),
                'birthdate' => array('type' => 'calendar'),
                'password' => array('type' => 'password'),
                'optional_textfield1' => array('type' => 'text'),
                'optional_textfield2' => array('type' => 'text'),
                'optional_textfield3' => array('type' => 'text'),
                'optional_textfield4' => array('type' => 'text'),
                'optional_textfield5' => array('type' => 'text'),
                'optional_textfield6' => array('type' => 'text'),
                'optional_checkbox1' => array('type' => 'checkbox'),
                'optional_checkbox2' => array('type' => 'checkbox'),
                'optional_checkbox3' => array('type' => 'checkbox'),
                'optional_checkbox4' => array('type' => 'checkbox'),
                'optional_date1' => array('type' => 'calendar'),
                'optional_date2' => array('type' => 'calendar'),
                'optional_select1' => array('type' => 'RegwebOptionalSelect'),
                'optional_select2' => array('type' => 'RegwebOptionalSelect'),
                'optional_select3' => array('type' => 'RegwebOptionalSelect'),
                'optional_select4' => array('type' => 'RegwebOptionalSelect'));

            // Gather config for label, whether to show and edit the field
            $params = new JRegistry(JPluginHelper::getPlugin('user', 'regweb')->params);
            foreach (array_keys($fieldsConfig) as $key) {
                $fieldsConfig[$key]['show'] 	= $params->get('config_'.$key.'_show', '');
                $fieldsConfig[$key]['edit'] 	= $params->get('config_'.$key.'_edit', '');
                $fieldsConfig[$key]['label'] 	= $params->get('config_'.$key.'_label', '');
            }
            // Field specific params
            $fieldsConfig['password']['repeat_label'] = $params->get('config_password_repeat_label');
        }
        return $fieldsConfig;
    }

    public function getForm($data = array(), $loadData = true)
    {
        $fieldsConfig = $this->getFieldsConfig();

        $fieldsXml = array();
        foreach ($fieldsConfig as $fieldName => $fieldConfig) {
            if (!$fieldConfig['edit']) {
                continue;
            }

            switch ($fieldName) {
                case 'password':
                    $fieldsXml[] = '<field name="password" id="password" type="password" label="'.
                        $fieldConfig['label'].'"/>';
                    $fieldsXml[] = '<field name="password2" id="password2" type="password" label="'.
                        $fieldConfig['repeat_label'].'"/>';
                    break;
                default:
                    switch ($fieldConfig['type']) {
                        case 'calendar':
                            $fieldsXml[] = 	'<field name="'.$fieldName.'" id="'.$fieldName.'" '.
                                'type="calendar" '.
                                'label="'.$fieldConfig['label'].'" '.
                                'format="%d.%m.%Y" '.
                                'description="Dato i format dd.mm.yyyy" '.
                                '/>';
                            break;
                        default:
                            $fieldsXml[] = 	'<field name="'.$fieldName.'" id="'.$fieldName.'" '.
                                'type="'.$fieldConfig['type'].'" '.
                                'label="'.$fieldConfig['label'].'" />';
                    }
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?><form><fields name="regweb">';
        $xml .= '<fieldset name="regweb" label="Personalia">';
        $xml .= implode('', $fieldsXml);
        $xml .= '</fieldset></fields></form>';
        // Load the generated form xml
        return $this->loadForm(	'com_regweb.profile',
            $xml,
            array(
                'control' => 'jform',
                'file' => false,
                'load_data' => $loadData));
    }

    public function setFormData($data) {
        $this->formData = $data;
    }

    /**
     * Loads form data from Regweb service
     */
    protected function loadFormData()
    {
        if ($this->formData != null) {
            return $this->formData;
        }

        $data = array('regweb' => array());
        $rwData = &$data['regweb'];
        $fieldsConfig = $this->getFieldsConfig();

        $regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
        if (!$regweb->api->isLoggedIn()) {
            return $data;
        }

        $userData = $regweb->api->getUser();
        if (!$userData->isMember) {
            return $data;
        }
        $memberData = $userData->member;

        // Basic fields
        if ($fieldsConfig['firstname']['edit']) {
            $rwData['firstname'] = $memberData->firstname;
        }
        if ($fieldsConfig['lastname']['edit']) {
            $rwData['lastname'] = $memberData->lastname;
        }
        if ($fieldsConfig['address1']['edit']) {
            $rwData['address1'] = $memberData->address1;
        }
        if ($fieldsConfig['address2']['edit']) {
            $rwData['address2'] = $memberData->address2;
        }
        if ($fieldsConfig['postalcode']['edit']) {
            $rwData['postalcode'] = $memberData->postalcode;
        }
        if ($fieldsConfig['phone1']['edit']) {
            $rwData['phone1'] = $memberData->phone1;
        }
        if ($fieldsConfig['phone2']['edit']) {
            $rwData['phone2'] = $memberData->phone2;
        }
        if ($fieldsConfig['mobile']['edit']) {
            $rwData['mobile'] = $memberData->mobile;
        }
        if ($fieldsConfig['email']['edit']) {
            $rwData['email'] = $memberData->email;
        }
        if ($fieldsConfig['birthdate']['edit']) {
            $rwData['birthdate'] = $memberData->birthdate;
        }

        // Textfields
        if ($fieldsConfig['optional_textfield1']['edit']) {
            $rwData['optional_textfield1'] = $memberData->optionalTextfield1;
        }
        if ($fieldsConfig['optional_textfield2']['edit']) {
            $rwData['optional_textfield1'] = $memberData->optionalTextfield2;
        }
        if ($fieldsConfig['optional_textfield3']['edit']) {
            $rwData['optional_textfield3'] = $memberData->optionalTextfield3;
        }
        if ($fieldsConfig['optional_textfield4']['edit']) {
            $rwData['optional_textfield4'] = $memberData->optionalTextfield4;
        }
        if ($fieldsConfig['optional_textfield5']['edit']) {
            $rwData['optional_textfield5'] = $memberData->optionalTextfield5;
        }
        if ($fieldsConfig['optional_textfield6']['edit']) {
            $rwData['optional_textfield6'] = $memberData->optionalTextfield6;
        }

        // Checkboxes
        if ($fieldsConfig['optional_checkbox1']['edit']) {
            $rwData['optional_checkbox1'] = $memberData->optionalCheckbox1;
        }
        if ($fieldsConfig['optional_checkbox2']['edit']) {
            $rwData['optional_checkbox2'] = $memberData->optionalCheckbox2;
        }
        if ($fieldsConfig['optional_checkbox3']['edit']) {
            $rwData['optional_checkbox3'] = $memberData->optionalCheckbox3;
        }
        if ($fieldsConfig['optional_checkbox4']['edit']) {
            $rwData['optional_checkbox4'] = $memberData->optionalCheckbox4;
        }

        // Dates
        if ($fieldsConfig['optional_date1']['edit']) {
            $rwData['optional_date1'] = $memberData->optionalDate1;
        }
        if ($fieldsConfig['optional_date2']['edit']) {
            $rwData['optional_date2'] = $memberData->optionalDate2;
        }

        // Selects
        if ($fieldsConfig['optional_select1']['edit']) {
            $rwData['optional_select1'] = $memberData->optionalSelect1;
        }
        if ($fieldsConfig['optional_select2']['edit']) {
            $rwData['optional_select2'] = $memberData->optionalSelect2;
        }
        if ($fieldsConfig['optional_select3']['edit']) {
            $rwData['optional_select3'] = $memberData->optionalSelect3;
        }
        if ($fieldsConfig['optional_select4']['edit']) {
            $rwData['optional_select4'] = $memberData->optionalSelect4;
        }

        $this->formData = $data;

        return $data;
    }

    public function save($data) {
        JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();

        // Make sure this is a logged in regweb user
        $regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
        if (!$regweb->api->isLoggedIn()) {
            return true;
        }

        $userData = $regweb->api->getUser();
        if (!$userData->isMember) {
            return true;
        }

        $fieldsConfig = $this->getFieldsConfig();
        $memberData = $userData->member;
        $rwData = &$data['regweb'];

        // Basic fields
        $memberData->firstname = ($fieldsConfig['firstname']['edit']) ? $rwData['firstname'] : null;
        $memberData->lastname = ($fieldsConfig['lastname']['edit']) ? $rwData['lastname'] : null;
        $memberData->address1 = ($fieldsConfig['address1']['edit']) ? $rwData['address1'] : null;
        $memberData->address2 = ($fieldsConfig['address2']['edit']) ? $rwData['address2'] : null;
        $memberData->postalcode = ($fieldsConfig['postalcode']['edit']) ? $rwData['postalcode'] : null;
        $memberData->phone1 = ($fieldsConfig['phone1']['edit']) ? $rwData['phone1'] : null;
        $memberData->phone2 = ($fieldsConfig['phone2']['edit']) ? $rwData['phone2'] : null;
        $memberData->mobile = ($fieldsConfig['mobile']['edit']) ? $rwData['mobile'] : null;
        $memberData->email = ($fieldsConfig['email']['edit']) ? $rwData['email'] : null;
        $memberData->birthdate = ($fieldsConfig['birthdate']['edit']) ? $rwData['birthdate'] : null;
        if ($memberData->birthdate !== null && $memberData->birthdate !== '') {
            $memberData->birthdate = DateTime::createFromFormat('d.m.Y', $memberData->birthdate)->format('Y-m-d');
        }

        if ($fieldsConfig['password']['edit']) {
            if ($rwData['password'] != '') {
                if ($rwData['password2'] != $rwData['password']) {
                    $app->enqueueMessage(JText::_('COM_REGWEB_PROFILE_PASSWORD_MISMATCH'), 'error');
                    return false;
                } else {
                    $memberData->password = $rwData['password'];
                }
            }
        }

        // Textfields
        $memberData->optionalTextfield1 = ($fieldsConfig['optional_textfield1']['edit']) ? $rwData['optional_textfield1'] : null;
        $memberData->optionalTextfield2 = ($fieldsConfig['optional_textfield2']['edit']) ? $rwData['optional_textfield2'] : null;
        $memberData->optionalTextfield3 = ($fieldsConfig['optional_textfield3']['edit']) ? $rwData['optional_textfield3'] : null;
        $memberData->optionalTextfield4 = ($fieldsConfig['optional_textfield4']['edit']) ? $rwData['optional_textfield4'] : null;
        $memberData->optionalTextfield5 = ($fieldsConfig['optional_textfield5']['edit']) ? $rwData['optional_textfield5'] : null;
        $memberData->optionalTextfield6 = ($fieldsConfig['optional_textfield6']['edit']) ? $rwData['optional_textfield6'] : null;

        // Checkboxes
        $memberData->optionalCheckbox1 = ($fieldsConfig['optional_checkbox1']['edit']) ? $rwData['optional_checkbox1'] : null;
        $memberData->optionalCheckbox2 = ($fieldsConfig['optional_checkbox2']['edit']) ? $rwData['optional_checkbox2'] : null;
        $memberData->optionalCheckbox3 = ($fieldsConfig['optional_checkbox3']['edit']) ? $rwData['optional_checkbox3'] : null;
        $memberData->optionalCheckbox4 = ($fieldsConfig['optional_checkbox4']['edit']) ? $rwData['optional_checkbox4'] : null;

        // Dates
        $memberData->optionalDate1 = ($fieldsConfig['optional_date1']['edit']) ? $rwData['optional_date1'] : null;
        if ($memberData->optionalDate1 !== null && $memberData->optionalDate1 !== '') {
            $memberData->optionalDate1 = DateTime::createFromFormat('d.m.Y', $memberData->optionalDate1)->format('Y-m-d');
        }
        $memberData->optionalDate2 = ($fieldsConfig['optional_date2']['edit']) ? $rwData['optional_date2'] : null;
        if ($memberData->optionalDate2 !== null && $memberData->optionalDate2 !== '') {
            $memberData->optionalDate2 = DateTime::createFromFormat('d.m.Y', $memberData->optionalDate2)->format('Y-m-d');
        }

        // Selects
        $memberData->optionalSelect1 = ($fieldsConfig['optional_select1']['edit']) ? $rwData['optional_select1'] : null;
        $memberData->optionalSelect2 = ($fieldsConfig['optional_select2']['edit']) ? $rwData['optional_select2'] : null;
        $memberData->optionalSelect3 = ($fieldsConfig['optional_select3']['edit']) ? $rwData['optional_select3'] : null;
        $memberData->optionalSelect4 = ($fieldsConfig['optional_select4']['edit']) ? $rwData['optional_select4'] : null;

        // Execute request and set error messages if not successful
        $response = $regweb->api->updateMember($memberData);
        if (!$response->success) {
            foreach ($response->errors as $field => $errors) {
                foreach ($errors as $error) {
                    $app->enqueueMessage($fieldsConfig[$field]['label'].': '.$error, 'error');
                }
            }
            return false;
        }

        // Update joomla user
        $joomlaUserData = array();
        if ($memberData->email) {
            $joomlaUserData['email'] = $memberData->email;
        }
        if (count($joomlaUserData)) {
            $joomlaUser = JFactory::getUser();
            $joomlaUser->bind($joomlaUserData);
            $joomlaUser->save(true);
        }

        return true;
    }

    public function getDisplayData()
    {
        $data = array();
        // Make sure this is a logged in regweb user
        $regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
        if (!$regweb->api->isLoggedIn()) {
            return $data;
        }

        $userData = $regweb->api->getUser();
        if (!$userData->isMember) {
            return $data;
        }

        $fieldsConfig = $this->getFieldsConfig();
        $memberData = $userData->member;

        // Basic fields
        if ($fieldsConfig['membernumber']['show']) { $data['membernumber'] = $memberData->id; }
        if ($fieldsConfig['firstname']['show']) { $data['firstname'] = $memberData->firstname; }
        if ($fieldsConfig['lastname']['show']) { $data['lastname'] = $memberData->lastname; }
        if ($fieldsConfig['address1']['show']) { $data['address1'] = $memberData->address1; }
        if ($fieldsConfig['address2']['show']) { $data['address2'] = $memberData->address2; }
        if ($fieldsConfig['postalcode']['show']) { $data['postalcode'] = $memberData->postalcode; }
        if ($fieldsConfig['phone1']['show']) { $data['phone1'] = $memberData->phone1; }
        if ($fieldsConfig['phone2']['show']) { $data['phone2'] = $memberData->phone2; }
        if ($fieldsConfig['mobile']['show']) { $data['mobile'] = $memberData->mobile; }
        if ($fieldsConfig['email']['show']) { $data['email'] = $memberData->email; }
        if ($fieldsConfig['birthdate']['show']) { $data['email'] = $memberData->birthdate; }

        // Textfields
        if ($fieldsConfig['optional_textfield1']['show']) {
            $data['optional_textfield1'] = $memberData->optionalTextfield1;
        }
        if ($fieldsConfig['optional_textfield2']['show']) {
            $data['optional_textfield2'] = $memberData->optionalTextfield2;
        }
        if ($fieldsConfig['optional_textfield3']['show']) {
            $data['optional_textfield3'] = $memberData->optionalTextfield3;
        }
        if ($fieldsConfig['optional_textfield4']['show']) {
            $data['optional_textfield4'] = $memberData->optionalTextfield4;
        }
        if ($fieldsConfig['optional_textfield5']['show']) {
            $data['optional_textfield5'] = $memberData->optionalTextfield5;
        }
        if ($fieldsConfig['optional_textfield6']['show']) {
            $data['optional_textfield6'] = $memberData->optionalTextfield6;
        }

        // Checkboxes
        if ($fieldsConfig['optional_checkbox1']['show']) {
            $data['optional_checkbox1'] = ($memberData->optionalCheckbox1) ? JText::_('JYES') : JText::_('JNO');
        }
        if ($fieldsConfig['optional_checkbox2']['show']) {
            $data['optional_checkbox2'] = ($memberData->optionalCheckbox2) ? JText::_('JYES') : JText::_('JNO');
        }
        if ($fieldsConfig['optional_checkbox3']['show']) {
            $data['optional_checkbox3'] = ($memberData->optionalCheckbox3) ? JText::_('JYES') : JText::_('JNO');
        }
        if ($fieldsConfig['optional_checkbox4']['show']) {
            $data['optional_checkbox4'] = ($memberData->optionalCheckbox4) ? JText::_('JYES') : JText::_('JNO');
        }

        // Dates
        if ($fieldsConfig['optional_date1']['show']) {
            $data['optional_date1'] = $memberData->optionalDate1;
        }
        if ($fieldsConfig['optional_date2']['show']) {
            $data['optional_date2'] = $memberData->optionalDate2;
        }

        // Selects
        if ($fieldsConfig['optional_select1']['show']) {
            $data['optional_select1'] = $memberData->optionalSelect1;
        }
        if ($fieldsConfig['optional_select2']['show']) {
            $data['optional_select2'] = $memberData->optionalSelect2;
        }
        if ($fieldsConfig['optional_select3']['show']) {
            $data['optional_select3'] = $memberData->optionalSelect3;
        }
        if ($fieldsConfig['optional_select4']['show']) {
            $data['optional_select4'] = $memberData->optionalSelect4;
        }

        return $data;
    }

}