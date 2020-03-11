<div class="wrap">
    <h2>WP Header Cleaner</h2>
    <form method="post" action="options.php"> 
    	<!-- After the opening form tag, add this function settings_fields( 'myoption-group' ); where myoption-group is the same name used in the register_setting function. -->
        <?php settings_fields('wp_header_cleaner_d222-group'); ?> 
	
		<!-- After the settings_fields() call, add this function do_settings_sections( 'myoption-group' ); This function replaces the form-field markup in the form itself. -->
        <?php do_settings_sections('wp_header_cleaner_d222'); ?>

        <?php submit_button(); ?>
    </form>
</div>