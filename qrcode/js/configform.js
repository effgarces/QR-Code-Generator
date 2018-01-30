/**
 * Config form helper.
 *
 * @package    mahara
 * @subpackage blocktype-qrcode
 * @author     Liip <https://www.liip.ch/>
 * @author     Ruslan Kabalin <ruslan.kabalin@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 */

function toggle_custom_size() {
    var customshidden = true;

    if ($j("#instconf_size_container input[name='size'][value='custom']").prop('checked')) {
        customshidden = false;
    }

    $j('#instconf_height_container').toggleClass("hidden", customshidden);
    $j('#instconf_width_container').toggleClass("hidden", customshidden);
    $j('#instconf_height_container input').toggleClass("hidden", customshidden);
    $j('#instconf_width_container input').toggleClass("hidden", customshidden);
}
