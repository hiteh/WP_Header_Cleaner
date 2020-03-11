<div class="wrap">
    <h2>WP Header Cleaner</h2>
    <form method="post" action="options.php"> 
        <?php settings_fields('wp_header_cleaner_d222-group'); ?> 
        <?php do_settings_sections('wp_header_cleaner_d222'); ?>
        <?php submit_button(); ?>
    </form>
</div>