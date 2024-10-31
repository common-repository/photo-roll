<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Instantgram
 * @subpackage Instantgram/admin/partials
 */
$public = new PRI_Instantgram_Public('instantgram', '1.0.0');
$cron = new PRI_Instantgram_Cron('instantgram', '1.0.0');
$cron->update();
$ig_profile = get_option('ig_profile');
?>
<div class="wrap">
    <h1><?php _e('Instantgram Settings', 'instantgram');?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields("section");
        do_settings_sections("instantgram-options");
        submit_button();
        ?>
    </form>
    <?php if(!empty($ig_profile)):?>
    <div>
        <h3><?php _e('Instantgram shortcode');?></h3>
        <div>
            <?php _e('Below you can find shortcode that is responsible for generating photo gallery html code. Just paste it in your editor and enjoy your instantgram journey','instantgram');?>
            <br /><br />
        </div>
        <span>
            [ig_instantgram_gallery]
            <br /><br />
        </span>
        <div>
            <?php _e('Below you can see preview of shortcode output','instantgram');?>
            <br /><br />
        </div>
        <?php echo $public->render_gallery()?>
    </div>
    <?php endif;?>
</div>