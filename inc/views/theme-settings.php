<div class="wrap theme-settings">

	<h1>Theme Settings</h1>

    <h2 class="nav-tab-wrapper">

        <a href="?page=theme-settings" class="nav-tab <?php echo ($active_tab == 'theme-settings' ? 'nav-tab-active' : '') ?> "><?php _e('Theme Settings', 'theme'); ?></a>
        <a href="?page=ad-placement-settings" class="nav-tab <?php echo ($active_tab == 'ad-placement-settings' ? 'nav-tab-active' : ''); ?>"><?php _e('Ad Placements', 'theme'); ?></a>

    </h2>

    <form action='options.php' method='post'>

        <?php
        switch($active_tab)
        {
            case 'ad-placement-settings':
                settings_fields('ad_placement_settings_group');
                do_settings_sections('ad-placement-settings');
                break;
            default:
				settings_fields('theme_settings_group');
				do_settings_sections('theme-settings');
                break;
        }

        submit_button();
        ?>

    </form>

</div>
