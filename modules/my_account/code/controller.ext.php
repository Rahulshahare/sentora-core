<?php

/**
 *
 * ZPanel - A Cross-Platform Open-Source Web Hosting Control panel.
 * 
 * @package ZPanel
 * @version $Id$
 * @author Bobby Allen - ballen@zpanelcp.com
 * @copyright (c) 2008-2011 ZPanel Group - http://www.zpanelcp.com/
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License v3
 *
 * This program (ZPanel) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
 
class module_controller {

	static $hasupdated;

	static function getAccountSettings (){
	
		$currentuser = ctrl_users::GetUserDetail();
	
 		$line  = "<tr>";
		$line .= "<th>".ui_language::translate('Full name').":</th>";
		$line .= "<td><input name=\"inFullname\" type=\"text\" id=\"inFullname\" size=\"40\" value=\"" . $currentuser['fullname'] . "\" /></td>";
		$line .= "</tr>";
		$line .= "<tr>";
		$line .= "<th>".ui_language::translate('Email Address').":</th>";
		$line .= "<td><input name=\"inEmail\" type=\"text\" id=\"inEmail\" size=\"40\" value=\"" . $currentuser['email'] . "\" /></td>";
		$line .= "</tr>";
		$line .= "<tr>";
		$line .= "<th>".ui_language::translate('Phone Number').":</th>";
		$line .= "<td><input name=\"inPhone\" type=\"text\" id=\"inPhone\" size=\"20\" value=\"" . $currentuser['phone'] . "\" /></td>";
		$line .= "</tr>";
		$line .= "<tr>";
		$line .= "<th>".ui_language::translate('Choose Language').":</th>";
		$line .= "<td>";
		$line .= "<select name=\"inLanguage\" id=\"inLanguage\" style=\"width:50px;\">";
		
		$column_names = ui_language::GetColumnNames('x_translations');
		foreach ($column_names as $column_name){
			if ($column_name != 'tr_id_pk'){
				$column_name = explode('_',$column_name);
				$lang = $column_name[1];
				if ($lang == $currentuser['language']){
					$selected = "SELECTED";
				} else {
					$selected = "";
				}
				$line .= "<option value=\"".$lang."\" ".$selected.">".$lang."</option>";
			}
		}	
			
		$line .= "</select>";
		$line .= "</td>";
		$line .= "</tr>";
		$line .= "<tr>";
		$line .= "<th>".ui_language::translate('Postal Address').":</th>";
		$line .= "<td><textarea name=\"inAddress\" id=\"inAddress\" cols=\"45\" rows=\"5\">" . $currentuser['address'] . "</textarea></td>";
		$line .= "</tr>";
		$line .= "<tr>";
		$line .= "<th>".ui_language::translate('Postal Code').":</th>";
		$line .= "<td><input name=\"inPostalCode\" type=\"text\" id=\"inPostalCode\" size=\"15\" value=\"" . $currentuser['postcode'] . "\" /></td>";
		$line .= "</tr>";
		$line .= "<tr>";
		$line .= "<th>&nbsp;</th>";
		$line .= "<td align=\"right\"><button class=\"fg-button ui-state-default ui-corner-all\" id=\"button\" type=\"submit\" >".ui_language::translate('Update Account')."</button</td>";
		$line .= "</tr>	";
	
	return $line;
	}
	
	
	static function doUpdateAccountSettings(){
		global $zdbh;
		global $controller;
		
		$currentuser = ctrl_users::GetUserDetail();
			
		$sql = $zdbh->prepare("UPDATE x_accounts SET ac_email_vc = '". $controller->GetControllerRequest('FORM', 'inEmail')."' WHERE ac_id_pk = '".$currentuser['userid']."'");
	 	$sql->execute();

		$sql = $zdbh->prepare("UPDATE x_profiles SET ud_fullname_vc = '". $controller->GetControllerRequest('FORM', 'inFullname')."',
													 ud_language_vc = '". $controller->GetControllerRequest('FORM', 'inLanguage')."',
													 ud_phone_vc = '". $controller->GetControllerRequest('FORM', 'inPhone')."',
													 ud_address_tx = '". $controller->GetControllerRequest('FORM', 'inAddress')."',
													 ud_postcode_vc = '". $controller->GetControllerRequest('FORM', 'inPostalCode')."' WHERE 
													 ud_user_fk = '".$currentuser['userid']."'");
	 	$sql->execute();	
		self::$hasupdated = "yes";
	}
	
	
	static function getResult() {
        if (!fs_director::CheckForEmptyValue(self::$hasupdated)){
            return ui_sysmessage::shout(ui_language::translate("Changes to your account settings have been saved successfully!"));
		}else{
			return ui_language::translate(ui_module::GetModuleDescription());
		}
        return;
    }


	static function getModuleName() {
		$module_name = ui_language::translate(ui_module::GetModuleName());
        return $module_name;
    }

	static function getModuleIcon() {
		global $controller;
		$module_icon = "/modules/" . $controller->GetControllerRequest('URL', 'module') . "/assets/icon.png";
        return $module_icon;
    }
	
	static function getHeader() {
		$message = ui_language::translate('Enter your account details');
        return $message;
    }	
}

?>
