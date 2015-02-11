<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.helper');
jimport('joomla.user.helper');

require_once JPATH_LIBRARIES . '/regweb/vendor/autoload.php';

class plgUserRegweb extends JPlugin {
	
	// Make members part of a configured usergroup
	function onUserLogin($user, $options) {
		$this->addToConfiguredUserGroup(JUserHelper::getUserId($user['username']));
	}
	
	function addToConfiguredUserGroup($userId) {
		if (!$userId) {
			return;
		}
		$regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
		if (!$regweb->api->isLoggedIn()) {
			return;
		}
		
		$authParams = new JRegistry(JPluginHelper::getPlugin('authentication', 'regweb')->params);
		$userGroup = $authParams->get('config_usergroup');
		if ($userGroup) {
			JUserHelper::addUserToGroup($userId, $userGroup);
		}
	}
	
	function getFieldsConfig() {
		$data = array(
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
		foreach (array_keys($data) as $key) {
			$data[$key]['show'] 	= $this->params->get('config_'.$key.'_show', '');
			$data[$key]['edit'] 	= $this->params->get('config_'.$key.'_edit', '');
			$data[$key]['label'] 	= $this->params->get('config_'.$key.'_label', '');
		}
		
		return $data;
	}
	
	function onContentPrepareData($context, $data) {
		$app = JFactory::getApplication();
		if ($app->isAdmin()) {
			// Abort when we are not connected through the
			// regweb user viewing it's profile data
			return true;
		}
		
		$allowedContexts = array(
			'com_users.profile',
			'com_users.registration',
			'com_users.user',
			'com_admin.profile');
		
		if (!in_array($context, $allowedContexts)) {
			return true;
		}
		
		// Make sure this is a logged in regweb user
		$regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
		if (!$regweb->api->isLoggedIn()) {
			return true;
		}
		
		$userData = $regweb->api->getUser();
		if (!$userData->isMember) {
			return true;
		}
		$memberData = $userData->member;
		
		// Overwrite email from the joomla system with regweb registered
		$data->email = $memberData->email;
		$data->email1 = $memberData->email;
		$data->email2 = $memberData->email;
		
		// Show different values depending on whether this is for a form or display
		$editMode = (JFactory::getApplication()->input->getCmd('layout') == 'edit');
		
		$data->regweb = array();
		$fieldsConfig = $this->getFieldsConfig();
		
		if ($editMode) {
			// Basic fields
			if ($fieldsConfig['firstname']['edit']) {
				$data->regweb['firstname'] = $memberData->firstname;
			}
			if ($fieldsConfig['lastname']['edit']) {
				$data->regweb['lastname'] = $memberData->lastname;
			}
			if ($fieldsConfig['address1']['edit']) {
				$data->regweb['address1'] = $memberData->address1;
			}
			if ($fieldsConfig['address2']['edit']) {
				$data->regweb['address2'] = $memberData->address2;
			}
			if ($fieldsConfig['postalcode']['edit']) {
				$data->regweb['postalcode'] = $memberData->postalcode;
			}
			if ($fieldsConfig['phone1']['edit']) {
				$data->regweb['phone1'] = $memberData->phone1;
			}
			if ($fieldsConfig['phone2']['edit']) {
				$data->regweb['phone2'] = $memberData->phone2;
			}
			if ($fieldsConfig['mobile']['edit']) {
				$data->regweb['mobile'] = $memberData->mobile;
			}
			if ($fieldsConfig['email']['edit']) {
				$data->regweb['email'] = $memberData->email;
			}
            if ($fieldsConfig['birthdate']['edit']) {
                $data->regweb['birthdate'] = $memberData->birthdate;
            }
			
			// Textfields
			if ($fieldsConfig['optional_textfield1']['edit']) {
				$data->regweb['optional_textfield1'] = $memberData->optionalTextfield1;
			}
			if ($fieldsConfig['optional_textfield2']['edit']) {
				$data->regweb['optional_textfield1'] = $memberData->optionalTextfield2;
			}
			if ($fieldsConfig['optional_textfield3']['edit']) {
				$data->regweb['optional_textfield3'] = $memberData->optionalTextfield3;
			}
			if ($fieldsConfig['optional_textfield4']['edit']) {
				$data->regweb['optional_textfield4'] = $memberData->optionalTextfield4;
			}
			if ($fieldsConfig['optional_textfield5']['edit']) {
				$data->regweb['optional_textfield5'] = $memberData->optionalTextfield5;
			}
			if ($fieldsConfig['optional_textfield6']['edit']) {
				$data->regweb['optional_textfield6'] = $memberData->optionalTextfield6;
			}
			
			// Checkboxes
			if ($fieldsConfig['optional_checkbox1']['edit']) {
				$data->regweb['optional_checkbox1'] = $memberData->optionalCheckbox1;
			}
			if ($fieldsConfig['optional_checkbox2']['edit']) {
				$data->regweb['optional_checkbox2'] = $memberData->optionalCheckbox2;
			}
			if ($fieldsConfig['optional_checkbox3']['edit']) {
				$data->regweb['optional_checkbox3'] = $memberData->optionalCheckbox3;
			}
			if ($fieldsConfig['optional_checkbox4']['edit']) {
				$data->regweb['optional_checkbox4'] = $memberData->optionalCheckbox4;
			}
			
			// Dates
			if ($fieldsConfig['optional_date1']['edit']) {
				$data->regweb['optional_date1'] = $memberData->optionalDate1;
			}
			if ($fieldsConfig['optional_date2']['edit']) {
				$data->regweb['optional_date2'] = $memberData->optionalDate2;
			}
			
			// Selects
			if ($fieldsConfig['optional_select1']['edit']) {
				$data->regweb['optional_select1'] = $memberData->optionalSelect1;
			}
			if ($fieldsConfig['optional_select2']['edit']) {
				$data->regweb['optional_select2'] = $memberData->optionalSelect2;
			}
			if ($fieldsConfig['optional_select3']['edit']) {
				$data->regweb['optional_select3'] = $memberData->optionalSelect3;
			}
			if ($fieldsConfig['optional_select4']['edit']) {
				$data->regweb['optional_select4'] = $memberData->optionalSelect4;
			}
		} else {
			// Basic fields
			if ($fieldsConfig['firstname']['show']) {
				$data->regweb['firstname'] = $memberData->firstname;
			}
			if ($fieldsConfig['lastname']['show']) {
				$data->regweb['lastname'] = $memberData->lastname;
			}
			if ($fieldsConfig['address1']['show']) {
				$data->regweb['address1'] = $memberData->address1;
			}
			if ($fieldsConfig['address2']['show']) {
				$data->regweb['address2'] = $memberData->address2;
			}
			if ($fieldsConfig['postalcode']['show']) {
				$data->regweb['postalcode'] = $memberData->postalcode;
			}
			if ($fieldsConfig['phone1']['show']) {
				$data->regweb['phone1'] = $memberData->phone1;
			}
			if ($fieldsConfig['phone2']['show']) {
				$data->regweb['phone2'] = $memberData->phone2;
			}
			if ($fieldsConfig['mobile']['show']) {
				$data->regweb['mobile'] = $memberData->mobile;
			}
			if ($fieldsConfig['email']['show']) {
				$data->regweb['email'] = $memberData->email;
			}
			
			// Textfields
			if ($fieldsConfig['optional_textfield1']['show']) {
				$data->regweb['optional_textfield1'] = $memberData->optionalTextfield1;
			}
			if ($fieldsConfig['optional_textfield2']['show']) {
				$data->regweb['optional_textfield1'] = $memberData->optionalTextfield2;
			}
			if ($fieldsConfig['optional_textfield3']['show']) {
				$data->regweb['optional_textfield3'] = $memberData->optionalTextfield3;
			}
			if ($fieldsConfig['optional_textfield4']['show']) {
				$data->regweb['optional_textfield4'] = $memberData->optionalTextfield4;
			}
			if ($fieldsConfig['optional_textfield5']['show']) {
				$data->regweb['optional_textfield5'] = $memberData->optionalTextfield5;
			}
			if ($fieldsConfig['optional_textfield6']['show']) {
				$data->regweb['optional_textfield6'] = $memberData->optionalTextfield6;
			}
				
			// Checkboxes
			if ($fieldsConfig['optional_checkbox1']['show']) {
				$data->regweb['optional_checkbox1'] = ($memberData->optionalCheckbox1) ? JText::_('JYES') : JText::_('JNO');
			}
			if ($fieldsConfig['optional_checkbox2']['show']) {
				$data->regweb['optional_checkbox2'] = ($memberData->optionalCheckbox2) ? JText::_('JYES') : JText::_('JNO');
			}
			if ($fieldsConfig['optional_checkbox3']['show']) {
				$data->regweb['optional_checkbox3'] = ($memberData->optionalCheckbox3) ? JText::_('JYES') : JText::_('JNO');
			}
			if ($fieldsConfig['optional_checkbox4']['show']) {
				$data->regweb['optional_checkbox4'] = ($memberData->optionalCheckbox4) ? JText::_('JYES') : JText::_('JNO');
			}
				
			// Dates
			if ($fieldsConfig['optional_date1']['show']) {
				$data->regweb['optional_date1'] = $memberData->optionalDate1;
			}
			if ($fieldsConfig['optional_date2']['show']) {
				$data->regweb['optional_date2'] = $memberData->optionalDate2;
			}
				
			// Selects
			if ($fieldsConfig['optional_select1']['show']) {
				$data->regweb['optional_select1'] = $memberData->optionalSelect1;
			}
			if ($fieldsConfig['optional_select2']['show']) {
				$data->regweb['optional_select2'] = $memberData->optionalSelect2;
			}
			if ($fieldsConfig['optional_select3']['show']) {
				$data->regweb['optional_select3'] = $memberData->optionalSelect3;
			}
			if ($fieldsConfig['optional_select4']['show']) {
				$data->regweb['optional_select4'] = $memberData->optionalSelect4;
			}
		}
		
		return true;
	}
	
	function onContentPrepareForm($form, $data) {
		$app = JFactory::getApplication();
		if ($app->isAdmin()) {
			// Abort when we are not connected through the
			// regweb user viewing it's profile data
			return true;
		}
		
		if (!($form instanceof JForm)) {
			$this->subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		
		// Make sure this is a logged in regweb user
		$regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
		if (!$regweb->api->isLoggedIn()) {
			return true;
		}
		
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_regweb', JPATH_ADMINISTRATOR);
		
		$fieldsConfig = $this->getFieldsConfig();
		
		switch ($form->getName()) {
			// Profile edit form
			case 'com_users.profile':
				$editMode = (JFactory::getApplication()->input->getCmd('layout') == 'edit');
				
				$fieldsXml = array();
				foreach ($fieldsConfig as $fieldName => $fieldConfig) {
					// Skip if editing and not configured for editing, or showing and not configured for showing.
					if (($editMode && !$fieldConfig['edit']) || (!$editMode && !$fieldConfig['show'])) {
						continue;
					}
					$fieldsXml[] = 	'<field name="'.$fieldName.'" id="'.$fieldName.'" '.
									'type="'.$fieldConfig['type'].'" '.
									'label="'.$fieldConfig['label'].'" />';
				}
				$xml = '<?xml version="1.0" encoding="UTF-8"?><form><fields name="regweb">';
				$xml .= '<fieldset name="regweb" label="Personalia">';
				$xml .= implode('', $fieldsXml);
				$xml .= '</fieldset></fields></form>';
				// Load the generated form xml
				$form->load($xml);
				break;
				
			// Registration form
			case 'com_users.registration':
			// Backend registration/edit form
				break;
			case 'com_users.user':
				break;
			default:
				// Unhandled form
				return true;
		}
	}
	
	function onUserAfterSave($data, $isNew, $result, $error) {
		$app = JFactory::getApplication();
		if ($app->isAdmin()) {
			// Abort when we are not connected through the
			// regweb user viewing it's profile data
			return true;
		}
		
		if (!$result) {
			return true;
		}
		
		// Make sure this is a logged in regweb user
		$regweb = JoomlaRegweb\JoomlaRegweb::getInstance();
		if (!$regweb->api->isLoggedIn()) {
			return true;
		}
		
		$userData = $regweb->api->getUser();
		if (!$userData->isMember) {
			return true;
		}
		
		// Make sure user is in configured user group
		$this->addToConfiguredUserGroup(JArrayHelper::getValue($data, 'id', 0, 'int'));
		
		$fieldsConfig = $this->getFieldsConfig();
		$memberData = $userData->member;
		$rwData = &$data['regweb'];
		
		// Email field
		$memberData->email 		= $data['email1'];
		
		// Password
		if ($data['password1'] != '') {
			// Joomla will handle error message if they don't match
			if ($data['password1'] == $data['password2']) {
				$memberData->password = $data['password1'];
			}
		}
		// Basic fields
		if ($fieldsConfig['firstname']['edit']) {
			$memberData->firstname = $rwData['firstname'];
		}
		if ($fieldsConfig['lastname']['edit']) {
			$memberData->lastname = $rwData['lastname'];
		}
		if ($fieldsConfig['address1']['edit']) {
			$memberData->address1 = $rwData['address1'] ;
		}
		if ($fieldsConfig['address2']['edit']) {
			$memberData->address2 = $rwData['address2'];
		}
		if ($fieldsConfig['postalcode']['edit']) {
			$memberData->postalcode = $rwData['postalcode'];
		}
		if ($fieldsConfig['phone1']['edit']) {
			$memberData->phone1 = $rwData['phone1'];
		}
		if ($fieldsConfig['phone2']['edit']) {
			$memberData->phone2 = $rwData['phone2'];
		}
		if ($fieldsConfig['mobile']['edit']) {
			$memberData->mobile = $rwData['mobile'];
		}
		if ($fieldsConfig['email']['edit']) {
            $memberData->email = $rwData['email'];
        }
        if ($fieldsConfig['birthdate']['edit']) {
            $memberData->email = $rwData['birthdate'];
        }
			
		// Textfields
		if ($fieldsConfig['optional_textfield1']['edit']) {
			$memberData->optionalTextfield1 = $rwData['optional_textfield1'];
		}
		if ($fieldsConfig['optional_textfield2']['edit']) {
			$memberData->optionalTextfield2 = $rwData['optional_textfield1'];
		}
		if ($fieldsConfig['optional_textfield3']['edit']) {
			$memberData->optionalTextfield3 = $rwData['optional_textfield3'];
		}
		if ($fieldsConfig['optional_textfield4']['edit']) {
			$memberData->optionalTextfield4 = $rwData['optional_textfield4'];
		}
		if ($fieldsConfig['optional_textfield5']['edit']) {
			$memberData->optionalTextfield5 = $rwData['optional_textfield5'];
		}
		if ($fieldsConfig['optional_textfield6']['edit']) {
			$memberData->optionalTextfield6 = $rwData['optional_textfield6'];
		}
			
		// Checkboxes
		if ($fieldsConfig['optional_checkbox1']['edit']) {
			$memberData->optionalCheckbox1 = (isset($rwData['optional_checkbox1']) && $rwData['optional_checkbox1'] == '1') ? '1' : '0';
		}
		if ($fieldsConfig['optional_checkbox2']['edit']) {
			$memberData->optionalCheckbox2 = (isset($rwData['optional_checkbox2']) && $rwData['optional_checkbox2'] == '1') ? '1' : '0';
		}
		if ($fieldsConfig['optional_checkbox3']['edit']) {
			$memberData->optionalCheckbox3 = (isset($rwData['optional_checkbox3']) && $rwData['optional_checkbox3'] == '1') ? '1' : '0';
		}
		if ($fieldsConfig['optional_checkbox4']['edit']) {
			$memberData->optionalCheckbox4 = (isset($rwData['optional_checkbox4']) && $rwData['optional_checkbox4'] == '1') ? '1' : '0';
		}
			
		// Dates
		if ($fieldsConfig['optional_date1']['edit']) {
			$memberData->optionalDate1 = $rwData['optional_date1'];
		}
		if ($fieldsConfig['optional_date2']['edit']) {
			$memberData->optionalDate2 = $rwData['optional_date2'];
		}
			
		// Selects
		if ($fieldsConfig['optional_select1']['edit']) {
			$memberData->optionalSelect1 = $rwData['optional_select1'];
		}
		if ($fieldsConfig['optional_select2']['edit']) {
			$memberData->optionalSelect2 = $rwData['optional_select2'];
		}
		if ($fieldsConfig['optional_select3']['edit']) {
			$memberData->optionalSelect3 = $rwData['optional_select3'];
		}
		if ($fieldsConfig['optional_select4']['edit']) {
			$memberData->optionalSelect4 = $rwData['optional_select4'];
		}
		// Execute request and set error messages if not successful
		$response = $regweb->api->updateMember($memberData);
		if (!$response->success) {
			foreach ($response->errors as $field => $errors) {
				foreach ($errors as $error) {
					$this->_subject->setError($field . ': ' . $error);
				}
			}
			return false;
		}
		return true;
	}
	
	function onUserAfterDelete($user, $success, $msg) {
		// After deletion of user
		return true;
	}
	
}