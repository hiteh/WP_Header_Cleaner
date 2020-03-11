<?php
if( !class_exists( 'WP_Header_Cleaner_Settings' ) )
{
	class WP_Header_Cleaner_Settings
	{
		/**
		 * Construct the plugin settings object
         *
         * @param array $settings_list
         * @return void
		 */
		public function __construct( array $settings_list )
		{
            $this->data = $settings_list;
		}

        /**
         * Init admin settings.
         *
         * @return void
         */
        public function init_admin_settings()
        {
            add_action( 'admin_init', array( $this, 'admin_settings_register' ) );
        }

        /**
         * Init admin menu.
         *
         * @return void
         */
        public function init_admin_menu()
        {
            add_action( 'admin_menu', array( $this, 'admin_menu_register' ) );
        }

        /**
         * Register admin settings.
         *
         * @return void
         */
        public function admin_settings_register()
        {
            $this->admin_settings_section_register();

            foreach ( $this->data as $setting => $priority )
            {
                register_setting('wp_header_cleaner_d222-group', $setting );
                
                $item = explode( '-', $setting );

                add_settings_field(
                    'wp_header_cleaner_d222-' . $setting, // Slug-name to identify the field. Used in the 'id' attribute of tags.
                    $item[1], // Formatted title of the field. Shown as the label for the field during output
                    array( $this, 'settings_field_input' ), // Function that fills the field with the desired form inputs. The function should echo its output.
                    'wp_header_cleaner_d222', // The slug-name of the settings page on which to show the section (general, reading, writing, ...)
                    'wp_header_cleaner_d222-section', //The slug-name of the section of the settings page in which to show the box.
                    array( // Extra arguments used when outputting the field.
                        'field' => $setting
                    )
                );
            }
        }

        /**
         * This function provides text inputs for settings fields
         *
         * @param array $args
         * @return void
         */
        public function settings_field_input( array $args )
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $checked = get_option( $field ) == 1 ? 'checked' : '';
            // Display input checkbox
            echo sprintf(
                '<input type="checkbox" name="%1$s" id="%2$s" value="1" %3$s />', 
                esc_attr( $field ),
                esc_attr( $field ),
                esc_attr( $checked )
            );
        }

        /**
         * Register admin settings.
         *
         * @return void
         */
        public function admin_settings_section_register()
        {
            // Add settings section
            add_settings_section(
                'wp_header_cleaner_d222-section', // Slug-name to identify the section. Used in the 'id' attribute of tags.
                __( 'Settings', 'wp_header_cleaner_d222' ), // Formatted title of the section. Shown as the heading for the section.
                array( $this, 'settings_section_info' ) , // Function that echos out any content at the top of the section (between heading and fields).
                'wp_header_cleaner_d222' // The slug-name of the settings page on which to show the section. Built-in pages include 'general', 'reading', 'writing', 'discussion', 'media', etc. Create your own using add_options_page();
            );
        }

        /**
         * Registers a new settings page.
         *
         * @return void
         */
        public function admin_menu_register() {
            add_options_page(
                __( 'WP Header Cleaner', 'wp_header_cleaner_d222' ), // The text to be displayed in the title tags of the page when the menu is selected ( see page head )
                __( 'WP Header Cleaner', 'wp_header_cleaner_d222' ), // The text to be used for the menu
                'manage_options', // The capability required for this menu to be displayed to the user
                'wp_header_cleaner_d222', // he slug name to refer to this menu by (should be unique for this menu).
                array( $this, 'settings_page' ), // The function to be called to output the content for this page
                99 // The position in the menu order this item should appear
            );
        }
 
        /**
         * Settings page display callback.
         *
         * @return void
         */
        public function settings_page() 
        {
            // Render the settings template
            include( sprintf(
                "%s/templates/settings_page.php", 
                dirname(__FILE__)
            ) );
        }

        /**
         * Display info at the top of the section.
         *
         * @return void
         */
        public function settings_section_info()
        {
            echo __( 'Select items you want to remove from the header', 'wp_header_cleaner_d222' );
        }
	
    }
}