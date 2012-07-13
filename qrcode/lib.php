<?php
/**
 * Mahara: Electronic portfolio, weblog, resume builder and social networking
 * Copyright (C) 2006-2009 Catalyst IT Ltd and others; see:
 *                         http://wiki.mahara.org/Contributors
 *
 * This program is free software: you can redistribute it and/or modify
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
 * @package    mahara
 * @subpackage blocktype-qrcode
 * @author     Emanuel Garcês
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2011 Emanuel Garcês
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeQRCode extends SystemBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.qrcode');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.qrcode');
    }

    public static function get_categories() {
        return array('general');
    }

    public static function get_instance_config_javascript() {
        return array('js/configform.js');
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        $configdata = $instance->get('configdata');
        $size      	= (!empty($configdata['size']) ? hsc($configdata['size']) : 'medium');
        $height     = (!empty($configdata['height']) ? hsc($configdata['height']) : '200');
        $width      = (!empty($configdata['width']) ? hsc($configdata['width']) : '200');
        $type     	= (!empty($configdata['type']) ? hsc($configdata['type']) : 'profile');
        
        $page     	= hsc($configdata['page']);
        $first_name	= hsc($configdata['first_name']);
        $last_name	= hsc($configdata['last_name']);
        $address	= hsc($configdata['address']);
        $phone_number	= hsc($configdata['phone_number']);
        $smsmessage	= hsc($configdata['smsmessage']);
        $email	= hsc($configdata['email']);
        $subject	= hsc($configdata['subject']);
        $website_url = hsc($configdata['website_url']);
        $textmessage     = hsc($configdata['textmessage']);
        
        $align      = (!empty($configdata['align']) ? hsc($configdata['align']) : 'left');

		// Are we in editing or viewing mode?
		$mode = (!strpos(get_script_path(), 'blocks.php') ? 'view' : 'edit');
		
		switch ($size){
			case 'small': $height = '100'; $width = '100';
			break;
			case 'medium': $height = '200'; $width = '200';
			break;
			case 'big': $height = '300'; $width = '300';
			break;
		}

		switch ($type){
			case 'profile':
				$qrcode = get_config('wwwroot') . 'user/view.php?id=' . $instance->get_view()->get('owner');
			break;
			case 'current_page':
				$qrcode = get_config('wwwroot') . 'view/view.php?id=' . param_integer('id');;
			break;
			case 'page':
				$qrcode = get_config('wwwroot') . 'view/view.php?id=' . $page;
			break;
			case 'vcard':
				if (!empty($last_name) || !empty($first_name)) {
					$qrcode = 'BEGIN:VCARD' . PHP_EOL;
					$qrcode = $qrcode . 'N:' . $last_name;
					if (!empty($last_name)) $qrcode = $qrcode . ';' . $first_name . PHP_EOL;
					else $qrcode = $qrcode . $first_name . PHP_EOL;
					if (!empty($address)) $qrcode = $qrcode . 'ADR:' . $address . PHP_EOL;
					if (!empty($phone_number)) $qrcode = $qrcode . 'TEL:' . $phone_number . PHP_EOL;
					if (!empty($email)) $qrcode = $qrcode . 'EMAIL:' . $email . PHP_EOL;
					if (!empty($website_url)) $qrcode = $qrcode . 'URL:' . $website_url . PHP_EOL;
					$qrcode = $qrcode . 'END:VCARD';
				}
			break;
			case 'mecard':
				if (!empty($last_name) || !empty($first_name)) {
					$qrcode = 'MECARD:N:' . $last_name;
					if (!empty($last_name)) $qrcode = $qrcode . ',' . $first_name . ';';
					else $qrcode = $qrcode . $first_name . ';';
					if (!empty($address)) $qrcode = $qrcode . 'ADR:' . $address . ';';
					if (!empty($phone_number)) $qrcode = $qrcode . 'TEL:' . $phone_number . ';';
					if (!empty($email)) $qrcode = $qrcode . 'EMAIL:' . $email . ';';
					if (!empty($website_url)) $qrcode = $qrcode . 'URL:' . $website_url . ';';
					$qrcode = $qrcode . ';';
				}
			break;
			case 'email':
				if (!empty($email)) $qrcode = 'MAILTO:' . $email;
			break;
			case 'emailmessage':
				if (!empty($email) && !empty($textmessage)) $qrcode = 'MATMSG:TO:'. $email . ';SUB:' . $subject . ';BODY:' . $textmessage . ';;';
			break;
			case 'phone':
				if (!empty($phone_number)) $qrcode = 'TEL:' . $phone_number;
			break;
			case 'sms':
				if (!empty($phone_number) && !empty($smsmessage)) $qrcode = 'TEL:' . $phone_number . ':' . $smsmessage;
			break;
			case 'text':
				if (!empty($textmessage)) $qrcode = $textmessage;
			break;
			case 'url':
				if (!empty($website_url)) $qrcode = $website_url;
			break;
		}
        $smarty = smarty_core();
        $smarty->assign('align', $align);
		$smarty->assign('width', $width);
		$smarty->assign('height', $height);
        $smarty->assign('qrcode', rawurlencode($qrcode));
        $smarty->assign('mode', $mode);

        return $smarty->fetch('blocktype:qrcode:qrcode.tpl');
    }

    public static function has_instance_config() {
        return true;
    }

    public static function instance_config_form($instance) {
        $configdata = $instance->get('configdata');
        $userid = $instance->get_view()->get('owner');
        $pages = get_records_sql_array("
            SELECT c.id, c.title
                FROM {view} c
            WHERE owner = ?
            ORDER BY id, title ASC", array($userid));
		foreach ($pages as $page) {
			switch ($page->title){
				case 'Profile page': $options[$page->id] = get_string('profileviewtitle', 'view');
				break;
				case 'Dashboard page': $options[$page->id] = get_string('dashboardviewtitle', 'view');
				break;
				default: $options[$page->id] = $page->title;
			}
        }
        return array(
            'showtitle' => array(
                'type'  => 'checkbox',
                'title' => get_string('showtitle','blocktype.qrcode'),
                'defaultvalue' => (!empty($configdata['showtitle']) ? hsc($configdata['showtitle']) : 0),
            ),
			'size' => array(
				'type' => 'radio',
				'title' => get_string('size','blocktype.qrcode'),
				'defaultvalue' => (!empty($configdata['size']) ? hsc($configdata['size']) : 'medium'),
				'options' => array(
					'small' => get_string('size_small','blocktype.qrcode'),
					'medium' => get_string('size_medium','blocktype.qrcode'),
					'big' => get_string('size_big','blocktype.qrcode'),
					'custom' => get_string('size_custom','blocktype.qrcode'),
				),
				'separator' => '&nbsp;&nbsp;&nbsp;',
			),
			'height' => array(
				'type' => 'text',
				'title' => get_string('height','blocktype.qrcode'),
				'size' => 1,
				'defaultvalue' => (!empty($configdata['height']) ? hsc($configdata['height']) : '200'),
				'class' => ((isset($configdata['size']) && hsc($configdata['size']) == 'custom') ? '' : 'hidden'),
			),
			'width' => array(
				'type' => 'text',
				'title' => get_string('width','blocktype.qrcode'),
				'size' => 1,
				'defaultvalue' => (!empty($configdata['width']) ? hsc($configdata['width']) : '200'),
				'class' => ((isset($configdata['size']) && hsc($configdata['size']) == 'custom') ? '' : 'hidden'),
			),
            'type' => array(
                'type'  => 'select',
                'title' => get_string('type','blocktype.qrcode'),
                'defaultvalue' => (!empty($configdata['type']) ? hsc($configdata['type']) : 'profile'),
				'options' => array(
					'profile' => get_string('type_profile','blocktype.qrcode'),
					'current_page'   => get_string('type_current_page','blocktype.qrcode'),
					'page'   => get_string('type_page','blocktype.qrcode'),
					'vcard'   => get_string('type_vcard','blocktype.qrcode'),
					'mecard'   => get_string('type_mecard','blocktype.qrcode'),
					'email'   => get_string('type_email','blocktype.qrcode'),
					'emailmessage'   => get_string('type_emailmessage','blocktype.qrcode'),
					'phone'   => get_string('type_phone','blocktype.qrcode'),
					'sms'   => get_string('type_sms','blocktype.qrcode'),
					'text'   => get_string('type_text','blocktype.qrcode'),
					'url'   => get_string('type_url','blocktype.qrcode'),
				),
				'help' => true,
            ),
			'page' => array(
                    'type' => 'select',
                    'title' => get_string('page','blocktype.qrcode'),
                    'options' => $options,
        			'defaultvalue' => hsc($configdata['page']),
					'class' => ((isset($configdata['type']) && hsc($configdata['type']) == 'page') ? '' : 'hidden'),
                ),
			'first_name' => array(
				'type' => 'text',
				'title' => get_string('first_name','blocktype.qrcode'),
				'size' => 25,
        		'defaultvalue' => hsc($configdata['first_name']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'vcard' || hsc($configdata['type']) == 'mecard')) ? '' : 'hidden'),
			),
			'last_name' => array(
				'type' => 'text',
				'title' => get_string('last_name','blocktype.qrcode'),
				'size' => 25,
        		'defaultvalue' => hsc($configdata['last_name']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'vcard' || hsc($configdata['type']) == 'mecard')) ? '' : 'hidden'),
			),
			'address' => array(
				'type' => 'text',
				'title' => get_string('address','blocktype.qrcode'),
				'size' => 80,
        		'defaultvalue' => hsc($configdata['address']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'vcard' || hsc($configdata['type']) == 'mecard')) ? '' : 'hidden'),
			),
			'phone_number' => array(
				'type' => 'text',
				'title' => get_string('phone_number','blocktype.qrcode'),
				'size' => 15,
        		'defaultvalue' => hsc($configdata['phone_number']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'vcard' || hsc($configdata['type']) == 'mecard' || hsc($configdata['type']) == 'phone' || hsc($configdata['type']) == 'sms')) ? '' : 'hidden'),
			),
        	'smsmessage' => array(
                'type' => 'textarea',
                'title' => get_string('type_sms','blocktype.qrcode'),
        		'rows' => 5,
                'cols' => 90,
                'rules' => array('maxlength' => 160),
        		'defaultvalue' => hsc($configdata['smsmessage']),
        		'class' => ((isset($configdata['type']) && hsc($configdata['type']) == 'sms') ? '' : 'hidden'),
        	),
			'email' => array(
				'type' => 'text',
				'title' => get_string('email','blocktype.qrcode'),
				'size' => 50,
        		'defaultvalue' => hsc($configdata['email']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'email' || hsc($configdata['type']) == 'emailmessage' || hsc($configdata['type']) == 'vcard' || hsc($configdata['type']) == 'mecard')) ? '' : 'hidden'),
			),
        	'subject' => array(
        		'type' => 'text',
        		'title' => get_string('subject','blocktype.qrcode'),
        		'size' => 80,
        		'defaultvalue' => hsc($configdata['subject']),
        		'class' => ((isset($configdata['type']) && hsc($configdata['type']) == 'emailmessage') ? '' : 'hidden'),
        	),
			'website_url' => array(
				'type' => 'text',
				'title' => get_string('website_url','blocktype.qrcode'),
				'size' => 80,
        		'defaultvalue' => hsc($configdata['website_url']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'url' || hsc($configdata['type']) == 'vcard' || hsc($configdata['type']) == 'mecard')) ? '' : 'hidden'),
			),
            'textmessage' => array(
                'type' => 'textarea',
                'title' => get_string('type_text','blocktype.qrcode'),
				'rows' => 5,
                'cols' => 90,
        		'defaultvalue' => hsc($configdata['textmessage']),
				'class' => ((isset($configdata['type']) && (hsc($configdata['type']) == 'text' || hsc($configdata['type']) == 'emailmessage')) ? '' : 'hidden'),
            ),
            'align' => array(
                'type' => 'radio',
                'title' => get_string('align','blocktype.qrcode'),
                'defaultvalue' => (!empty($configdata['align'])) ? hsc($configdata['align']) : 'left',
				'options' => array(
					'left' => get_string('alignleft','blocktype.qrcode'),
					'center' => get_string('aligncenter','blocktype.qrcode'),
					'right' => get_string('alignright','blocktype.qrcode'),
				),
				'separator' => '&nbsp;&nbsp;&nbsp;',
			),
        );
    }

    public static function instance_config_save($values) {
		global $SESSION;
		
		
    	if (empty($values['first_name']) && empty($values['last_name']) && ($values['type']=='vcard' || $values['type']=='mecard')) {
    		$SESSION->add_error_msg(get_string('missing_name', 'blocktype.qrcode'));
    	}
    	if (empty($values['email']) && ($values['type']=='email')) {
    		$SESSION->add_error_msg(get_string('missing_email', 'blocktype.qrcode'));
    	}
    	if (empty($values['email']) && empty($values['text']) && ($values['type']=='emailmessage')) {
    		$SESSION->add_error_msg(get_string('missing_emailmessage', 'blocktype.qrcode'));
    	}
    	if (empty($values['phone_number']) && ($values['type']=='phone')) {
    		$SESSION->add_error_msg(get_string('missing_phone', 'blocktype.qrcode'));
    	}
    	if (empty($values['phone_number']) && empty($values['smsmessage']) && ($values['type']=='sms')) {
    		$SESSION->add_error_msg(get_string('missing_sms', 'blocktype.qrcode'));
    	}
    	if (empty($values['textmessage']) && ($values['type']=='text')) {
    		$SESSION->add_error_msg(get_string('missing_text', 'blocktype.qrcode'));
    	}
    	if (empty($values['website_url']) && ($values['type']=='url')) {
    		$SESSION->add_error_msg(get_string('missing_website_url', 'blocktype.qrcode'));
    	}
        if (empty($values['showtitle'])) {
			$values['title'] = null;
		}
		
		return $values;
	}
	
    public static function default_copy_type() {
        return 'full';
    }

}

?>
