<?php
/**
 * Plugin Name: Toggle Admin Notices
 * Description: A plugin to toggle admin notices visibility in the WordPress backend.
 * Version: 1.0
 * Author: Geoff Cordner
 */

// Add a settings page to toggle admin notices.
add_action( 'admin_menu', 'toggle_admin_notices_menu' );

function toggle_admin_notices_menu() {
	add_options_page(
		'Admin Notices Toggle',
		'Admin Notices Toggle',
		'manage_options',
		'toggle-admin-notices',
		'admin_notices_settings_page'
	);
}

// Render the settings page.
function admin_notices_settings_page() {
	?>
	<div class="wrap">
		<h1><?php _e( 'Toggle Admin Notices', 'toggle-admin-notices' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'admin_notices_settings_group' );
			do_settings_sections( 'toggle-admin-notices' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

// Register the setting for hiding admin notices.
add_action( 'admin_init', 'toggle_admin_notices_settings' );

function toggle_admin_notices_settings() {
	register_setting( 'admin_notices_settings_group', 'hide_admin_notices' );

	add_settings_section(
		'admin_notices_section',
		'Admin Notices Visibility',
		null,
		'toggle-admin-notices'
	);

	add_settings_field(
		'hide_admin_notices',
		'Hide Admin Notices for All Users',
		'toggle_admin_notices_callback',
		'toggle-admin-notices',
		'admin_notices_section'
	);
}

function toggle_admin_notices_callback() {
	$option = get_option( 'hide_admin_notices' );
	?>
	<input type="checkbox" name="hide_admin_notices" value="yes" <?php checked( $option, 'yes' ); ?> />
	<label for="hide_admin_notices"><?php _e( 'Check this box to hide admin notices for all users.', 'toggle-admin-notices' ); ?></label>
	<?php
}

// Conditionally hide admin notices for all users if the setting is enabled.
add_action( 'admin_init', 'conditionally_hide_admin_notices' );

function conditionally_hide_admin_notices() {
	// If the setting is enabled, remove all admin notices.
	if ( get_option( 'hide_admin_notices' ) === 'yes' ) {
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}
}
