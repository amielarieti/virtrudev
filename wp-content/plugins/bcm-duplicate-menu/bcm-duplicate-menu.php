<?php

/*	Plugin Name:	BCM Duplicate Menu
	Plugin URL:		http://bcmdev.nl/
	Description:	The easiest way to duplicate your menu
	Author:			BCM.dev
	Version:		1.0.4
	Author URI:		http://bcmdev.nl/
	License:		GPLv2
	Text Domain:	bcm-duplicate-menu
*/

//Translate Plugin Title
__('BCM Duplicate Menu', 'bcm-duplicate-menu');

//Translate Plugin Description
__('The easiest way to duplicate your menu', 'bcm-duplicate-menu');

//Translate Plugin Author
__('BCM.dev', 'bcm-duplicate-menu');

//Translate Plugin URL
__('https://wordpress.org/plugins/bcm-duplicate-menu/', 'bcm-duplicate-menu');

if ( ! class_exists( 'bcmDuplicateMenu' ) ) {

    class bcmDuplicateMenu
    {

        // Lets run some basics
        function __construct()
        {
            
            // Add support for translations
            load_plugin_textdomain( 'bcm-duplicate-menu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

            // Add the Duplicate Menu button to the nav-menus admin-page
            add_action('admin_footer', array($this, 'duplicate_menu_btn'), 10);
            
            // Redirect the nav-menus to the required plugin pages
            add_action('admin_footer', array($this, 'start_duplicate'), 5);
            
        }

        // Add the Duplicate Menu button to the nav-menus admin-page
        function duplicate_menu_btn()
        {
            $current_screen = get_current_screen();
            $current_menu   = get_user_option ('nav_menu_recently_edited');
            if ($current_screen->id == 'nav-menus' && $_GET['menu'] != '0') {
                $return = '';
                $return.= '<div style="display:none;" class="DuplicateMenuButtonSpinner spinner is-active"></div><a class="DuplicateMenuButtonClick button button-large" href="?duplicate='.$current_menu.'">'.__('Duplicate Menu', 'bcm-duplicate-menu').'</a>';
                ?>
                <script type="text/javascript">
                    var update_menu_form = jQuery('#update-nav-menu');
                    update_menu_form.find('.publishing-action').append('<?php echo addslashes_gpc($return); ?>');
                    jQuery('.DuplicateMenuButtonClick').click(function() {
                        jQuery('.DuplicateMenuButtonSpinner').show();
                    });
                </script>
        
                <?php
            }
        }

        function start_duplicate()
        {
            $current_screen = get_current_screen();
            if ($current_screen->id == 'nav-menus') {
                if (isset($_GET['duplicate'])) {
                    $id = intval( $_GET['duplicate'] );
                    $source = wp_get_nav_menu_object($id);
                    $duplicate = $this->duplicate($_GET['duplicate'], $source->name. ' ' .__('(Copy)', 'bcm-duplicate-menu'));
                    if ($duplicate) {
                        ?>
                        <script type="text/javascript">
                            window.location.replace("<?php echo admin_url('nav-menus.php?action=edit&menu='.$duplicate); ?>");
                        </script>
                        <?php
                    } else {
                        ?>
                        <script type="text/javascript">
                            window.location.replace("<?php echo admin_url('nav-menus.php'); ?>");
                        </script>
                        <?php
                    }
                }
            }
        }

        function duplicate($id = null, $name = null)
        {
            
            // Sanity check
            if ( empty($id) || empty($name) ) {
                return false;
            }
    
            $id = intval($id);
            $name = sanitize_text_field($name);
            $source = wp_get_nav_menu_object($id);
            $source_items = wp_get_nav_menu_items($id);
            
            $menu_exists = wp_get_nav_menu_object($name);

            if (!$menu_exists) {
                $new_id = wp_create_nav_menu($name);
            } else {
                return $new_id = $this->duplicate($id, $name . ' ' . __('(Copy)', 'bcm-duplicate-menu'));
            }
 
            if (!$new_id || is_array($new_id)) {
                return false;
            }
    
            // Key is the original db ID, val is the new
            $rel = array();
    
            $i = 1;
            foreach ($source_items as $menu_item) {
                $args = array(
                    'menu-item-db-id'       => $menu_item->db_id,
                    'menu-item-object-id'   => $menu_item->object_id,
                    'menu-item-object'      => $menu_item->object,
                    'menu-item-position'    => $i,
                    'menu-item-type'        => $menu_item->type,
                    'menu-item-title'       => $menu_item->title,
                    'menu-item-url'         => $menu_item->url,
                    'menu-item-description' => $menu_item->description,
                    'menu-item-attr-title'  => $menu_item->attr_title,
                    'menu-item-target'      => $menu_item->target,
                    'menu-item-classes'     => implode( ' ', $menu_item->classes ),
                    'menu-item-xfn'         => $menu_item->xfn,
                    'menu-item-status'      => $menu_item->post_status
                );
    
                $parent_id = wp_update_nav_menu_item($new_id, 0, $args);
    
                $rel[$menu_item->db_id] = $parent_id;
    
                // did it have a parent? if so, we need to update with the NEW ID
                if ($menu_item->menu_item_parent) {
                    $args['menu-item-parent-id'] = $rel[$menu_item->menu_item_parent];
                    $parent_id = wp_update_nav_menu_item($new_id, $parent_id, $args);
                }
    
                // allow developers to run any custom functionality they'd like
                do_action('duplicate_menu_item', $menu_item, $args);
    
                $i++;
            }

            return $new_id;
        }
    }

    $bcmDuplicateMenu = new bcmDuplicateMenu();

}
