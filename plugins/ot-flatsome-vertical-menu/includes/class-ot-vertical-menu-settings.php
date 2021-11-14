<?php
/**
 * class-ot-vertical-menu-settings.php
 *
 * @since: 1.1.0
 * @version: 1.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class OTFL_Vertical_Menu_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_options_page(
            __('OT Flatsome Vertical Menu Settings', 'skype-live-chat'),
            __('OT FS Vertical Menu', 'skype-live-chat'),
            'manage_options',
            'ot-flatsome-vertical-menu',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option('ot_vm');

        $menu_event = isset($this->options['menu_event']) ? esc_attr($this->options['menu_event']) : 'click';

        ?>
        <div class="wrap">
            <h1><?php _e('OT Flatsome Vertical Menu Settings', 'skype-live-chat'); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields('ot_vm'); ?>
                <?php do_settings_sections('ot_vm'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Menu Title', 'skype-live-chat'); ?></th>
                        <td>
                            <input type="text" name="ot_vm[menu_title]" class="regular-text"
                                   value="<?php echo isset($this->options['menu_title']) ? esc_attr($this->options['menu_title']) : __('Danh mục sản phẩm', ''); ?>"/>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php _e('Event show', 'skype-live-chat'); ?></th>
                        <td>
                            <select class="regular-text" name="ot_vm[menu_event]" id="menu_event">
                                <option value="click" <?php selected($menu_event, 'click'); ?>><?php _e('Click to show', 'skype-live-chat'); ?></option>
                                <option value="hover" <?php selected($menu_event, 'hover'); ?>><?php _e('Hover to show', 'skype-live-chat'); ?></option>
                            </select>
                        </td>
                    </tr>

                </table>

                <?php submit_button(); ?>

            </form>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'ot_vm', // Option group
            'ot_vm', // Option name
            array($this, 'sanitize') // Sanitize
        );
    }

    public function sanitize($input)
    {
        $new_input = array();

        if (isset($input['menu_title'])) {
            $new_input['menu_title'] = sanitize_text_field($input['menu_title']);
        }
        if (isset($input['menu_event'])) {
            $new_input['menu_event'] = sanitize_text_field($input['menu_event']);
        }

        return $new_input;
    }
}

if (is_admin()) {
    new OTFL_Vertical_Menu_Settings();
}