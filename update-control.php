<?php
/*
 * Plugin Name: Update Control
 * Plugin URI: http://github.com/georgestephanis/update-control/
 * Description: Adds a manual toggle to the WordPress Admin Interface for managing auto-updates.
 * Author: George Stephanis
 * Version: 1.1.3
 * Author URI: http://stephanis.info/
 */

/**
 * Update Control Class
 */
class Stephanis_Update_Control {

	public static function go() {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'init', array( __CLASS__, 'setup_upgrade_filters' ) );
	}

	public static function setup_upgrade_filters() {
		$options = self::get_options();

		// Do these at priority 1, so other folks can easily override it.

		if ( 'no' == $options['active'] ) {
			add_filter( 'auto_upgrader_disabled', '__return_true', 1 );
		} else {

			if ( in_array( $options['core'], array( 'dev', 'major', 'minor' ) ) ) {
				add_filter( 'allow_' . $options['core'] . '_auto_core_updates', '__return_true', 1 );
			}

			if ( $options['plugin'] ) {
				add_filter( 'auto_update_plugin', '__return_true', 1 );
			}

			if ( $options['theme'] ) {
				add_filter( 'auto_update_theme', '__return_true', 1 );
			}

			if ( ! $options['translation'] ) {
				add_filter( 'auto_update_translation', '__return_false', 1 );
			}

		}

		if ( 'no' == $options['emailactive'] || ! ( $options['successemail'] || $options['failureemail'] || $options['criticalemail'] ) ) {
			add_filter( 'auto_core_update_send_email', '__return_false', 1 );
		} else {
			add_filter( 'auto_core_update_send_email', array( __CLASS__, 'filter_email' ), 1, 2 );
		}

	}

	public static function filter_email( $bool, $type ) {
		$options = self::get_options();

		if ( 'success' == $type && ! $options['successemail'] )
			return false;

		if ( 'fail' == $type && ! $options['failureemail'] )
			return false;

		if ( 'critical' == $type && ! $options['criticalemail'] )
			return false;

		return $bool;
	}

	public static function get_options() {
		$defaults = array(
			'active'			=> 'yes',
			'core'				=> 'minor',
			'plugin'			=> false,
			'theme'				=> false,
			'translation'		=> true,
			'emailactive'		=> 'yes',
			'successemail'		=> true,
			'failureemail'		=> true,
			'criticalemail'		=> true,
		);
		$args = get_option( 'update_control_options', array() );
		return wp_parse_args( $args, $defaults );
	}

	public static function get_option( $key ) {
		$options = self::get_options();
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}
		return null;
	}

	public static function register_settings() {
		add_settings_section(
			'update-control',
			esc_html__( 'Automatic Updates', 'update-control' ),
			array( __CLASS__, 'update_control_settings_section' ),
			'general'
		);

		if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED ) {
			return;
		}

		add_settings_field(
			'update_control_active',
			sprintf( '<label for="update_control_active">%1$s</label>', __( 'Automatic Updates Enabled?', 'update-control' ) ),
			array( __CLASS__, 'update_control_active_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_core',
			sprintf( '<label for="update_control_core">%1$s</label>', __( 'Automatic Core Update Level?', 'update-control' ) ),
			array( __CLASS__, 'update_control_core_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_plugin',
			sprintf( '<label for="update_control_plugin">%1$s</label>', __( 'Permit Automatic Plugin Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_plugin_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_theme',
			sprintf( '<label for="update_control_theme">%1$s</label>', __( 'Permit Automatic Theme Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_theme_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_translation',
			sprintf( '<label for="update_control_translation">%1$s</label>', __( 'Permit Automatic Translation Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_translation_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_email_active',
			sprintf( '<label for="update_control_email_active">%1$s</label>', __( 'Update Emails Enabled?', 'update-control' ) ),
			array( __CLASS__, 'update_control_email_active_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_email_success',
			sprintf( '<label for="update_control_email_success">%1$s</label>', __( 'Send Emails for Successful Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_email_success_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_email_failure',
			sprintf( '<label for="update_control_email_failure">%1$s</label>', __( 'Send Emails for Failed Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_email_failure_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_email_critical',
			sprintf( '<label for="update_control_email_critical">%1$s</label>', __( 'Send Emails for Critically Failed Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_email_critical_cb' ),
			'general',
			'update-control'
		);

		register_setting( 'general', 'update_control_options', array( __CLASS__, 'sanitize_options' ) );
	}

	public static function update_control_settings_section() {
		if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED ) : ?>
			<p id="update-control-settings-section">
				<?php _e( 'You have the <code>AUTOMATIC_UPDATER_DISABLED</code> constant set.  Automatic updates are disabled.', 'update-control' ); ?>
			</p>
		<?php else : ?>
			<p id="update-control-settings-section">
				<?php _e( 'This section lets you specify what areas of your WordPress install will be permitted to auto-update.', 'update-control' ); ?>
			</p>
			<script>
				jQuery(document).ready(function($){
					$('#update_control_active').change(function(){
						if ( 'yes' != $(this).val() )
							$('.update_control_dependency').attr( 'readonly', 'readonly' );
						else
							$('.update_control_dependency' ).removeAttr( 'readonly' );
					}).trigger('change');

					$('#update_control_email_active').change(function(){
						if ( 'yes' != $(this).val() )
							$('.update_control_email_dependency').attr( 'readonly', 'readonly' );
						else
							$('.update_control_email_dependency' ).removeAttr( 'readonly' );
					}).trigger('change');
				});
			</script>
			<style>
			.update_control_dependency[readonly],
			.update_control_email_dependency[readonly] {
				opacity: 0.4;
			}
			</style>
		<?php endif;
	}

	public static function update_control_active_cb() {
		?>
		<select id="update_control_active" name="update_control_options[active]">
			<option <?php selected( 'yes' == self::get_option( 'active' ) ); ?> value="yes"><?php _e( 'Yes', 'update-control' ); ?></option>
			<option <?php selected( 'no' == self::get_option( 'active' ) ); ?> value="no"><?php _e( 'No', 'update-control' ); ?></option>
		</select>
		<?php
	}

	public static function update_control_core_cb() {
		?>
		<select class="update_control_dependency" id="update_control_core" name="update_control_options[core]">
			<option <?php selected( 'minor' == self::get_option( 'core' ) ); ?> value="minor"><?php _e( 'Minor Updates', 'update-control' ); ?></option>
			<option <?php selected( 'major' == self::get_option( 'core' ) ); ?> value="major"><?php _e( 'Major Updates', 'update-control' ); ?></option>
			<option <?php selected( 'dev' == self::get_option( 'core' ) ); ?> value="dev"><?php _e( 'Development Updates', 'update-control' ); ?></option>
		</select>
		<?php
	}

	public static function update_control_plugin_cb() {
		?>
		<input type="checkbox" class="update_control_dependency" id="update_control_plugin" name="update_control_options[plugin]" <?php checked( self::get_option( 'plugin' ) ); ?> />
		<?php
	}

	public static function update_control_theme_cb() {
		?>
		<input type="checkbox" class="update_control_dependency" id="update_control_theme" name="update_control_options[theme]" <?php checked( self::get_option( 'theme' ) ); ?> />
		<?php
	}

	public static function update_control_translation_cb() {
		?>
		<input type="checkbox" class="update_control_dependency" id="update_control_translation" name="update_control_options[translation]" <?php checked( self::get_option( 'translation' ) ); ?> />
		<?php
	}

	public static function update_control_email_active_cb() {
		?>
		<select id="update_control_email_active" name="update_control_options[emailactive]">
			<option <?php selected( 'yes' == self::get_option( 'emailactive' ) ); ?> value="yes"><?php _e( 'Yes', 'update-control' ); ?></option>
			<option <?php selected( 'no' == self::get_option( 'emailactive' ) ); ?> value="no"><?php _e( 'No', 'update-control' ); ?></option>
		</select>
		<?php
	}

	public static function update_control_email_success_cb() {
		?>
		<input type="checkbox" class="update_control_email_dependency" id="update_control_email_success" name="update_control_options[successemail]" <?php checked( self::get_option( 'successemail' ) ); ?> />
		<?php
	}

	public static function update_control_email_failure_cb() {
		?>
		<input type="checkbox" class="update_control_email_dependency" id="update_control_email_failure" name="update_control_options[failureemail]" <?php checked( self::get_option( 'failureemail' ) ); ?> />
		<?php
	}

	public static function update_control_email_critical_cb() {
		?>
		<input type="checkbox" class="update_control_email_dependency" id="update_control_email_critical" name="update_control_options[criticalemail]" <?php checked( self::get_option( 'criticalemail' ) ); ?> />
		<?php
	}

	public static function sanitize_options( $options ) {
		$options = (array) $options;

		$options['active'] = ( in_array( $options['active'], array( 'yes', 'no' ) ) ? $options['active'] : 'yes' );
		$options['core'] = ( in_array( $options['core'], array( 'minor', 'major', 'dev' ) ) ? $options['core'] : 'minor' );
		$options['plugin'] = ! empty( $options['plugin'] );
		$options['theme']  = ! empty( $options['theme']  );
		$options['translation']  = ! empty( $options['translation']  );
		$options['emailactive'] = ( in_array( $options['emailactive'], array( 'yes', 'no' ) ) ? $options['emailactive'] : 'yes' );
		$options['successemail'] = ! empty( $options['successemail'] );
		$options['failureemail'] = ! empty( $options['failureemail'] );
		$options['criticalemail']  = ! empty( $options['criticalemail']  );

		return $options;
	}

}
add_action( 'init', array( 'Stephanis_Update_Control', 'go' ), 0 );