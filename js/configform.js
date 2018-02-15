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

jQuery(function($) {
    var toggle_custom_size = function() {
        var customshidden = true;

        if ($("#instconf_size_container input[name='size'][value='custom']").prop('checked')) {
            customshidden = false;
        }

        $('#instconf_width_container').toggleClass("hidden", customshidden);
        $('#instconf_width_container input').toggleClass("hidden", customshidden);
    }

    $("#instconf_size_container input").click(function(e) {
        toggle_custom_size();
    });
});
