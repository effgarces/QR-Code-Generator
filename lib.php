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
 * @author     Emanuel Garc�s
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2011 Emanuel Garc�s
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeQRCode extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.qrcode');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.qrcode');
    }

    public static function get_categories() {
        return array('general');
    }

    public static function render_instance(BlockInstance $instance, $editing = false) {
        $configdata = $instance->get('configdata');
        $size = (!empty($configdata['size']) ? hsc($configdata['size']) : 'medium');
        $width = (!empty($configdata['width']) ? hsc($configdata['width']) : '200');

        switch ($size) {
            case 'small':
                $width = '100';
                break;
            case 'medium':
                $width = '200';
                break;
            case 'big':
                $width = '300';
                break;
        }

        $align = (!empty($configdata['align']) ? hsc($configdata['align']) : 'left');

        // Point URL to the current view.
        $qrcode = get_config('wwwroot') . 'view/view.php?id=' . param_integer('id');

        // Render template.
        $smarty = smarty_core();
        $smarty->assign('align', $align);
        $smarty->assign('blockid', $instance->get('id'));
        $smarty->assign('kjuaconfobject', json_encode(array('text' => $qrcode, 'size' => $width, 'ecLevel' => 'M', 'rounded' => 50)));
        return $smarty->fetch('blocktype:qrcode:qrcode.tpl');
    }

    public static function has_instance_config() {
        return true;
    }

    public static function get_instance_config_javascript(BlockInstance $instance) {
        return array('js/configform.js');
    }

    public static function get_instance_javascript(BlockInstance $instance) {
        return array('js/kjua.min.js');
    }

    public static function instance_config_form($instance) {
        $configdata = $instance->get('configdata');

        return array(
            'size' => array(
                'type' => 'radio',
                'title' => get_string('size', 'blocktype.qrcode'),
                'defaultvalue' => (!empty($configdata['size']) ? hsc($configdata['size']) : 'medium'),
                'options' => array(
                    'small' => get_string('size_small', 'blocktype.qrcode'),
                    'medium' => get_string('size_medium', 'blocktype.qrcode'),
                    'big' => get_string('size_big', 'blocktype.qrcode'),
                    'custom' => get_string('size_custom', 'blocktype.qrcode'),
                ),
                'onclick' => 'toggle_custom_size();',
            ),
            'width' => array(
                'type' => 'text',
                'title' => get_string('width', 'blocktype.qrcode'),
                'size' => 1,
                'defaultvalue' => (!empty($configdata['width']) ? hsc($configdata['width']) : '200'),
                'class' => ((isset($configdata['size']) && hsc($configdata['size']) == 'custom') ? '' : 'hidden'),
            ),
            'align' => array(
                'type' => 'radio',
                'title' => get_string('align', 'blocktype.qrcode'),
                'defaultvalue' => (!empty($configdata['align'])) ? hsc($configdata['align']) : 'left',
                'options' => array(
                    'left' => get_string('alignleft', 'blocktype.qrcode'),
                    'center' => get_string('aligncenter', 'blocktype.qrcode'),
                    'right' => get_string('alignright', 'blocktype.qrcode'),
                ),
            ),
        );
    }

    public static function default_copy_type() {
        return 'full';
    }

    public static function get_css_icon($blocktypename) {
        return 'qrcode';
    }
}
