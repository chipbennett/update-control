<?php

/*
 * Plugin Name: Update Control
 * Plugin URI: http://github.com/georgestephanis/update-control/
 * Description: Adds a manual toggle to the WordPress Admin Interface for managing auto-updates.
 * Author: George Stephanis
 * Version: 1.0
 * Author URI: http://stephanis.info/
 */

class Stephanis_Update_Control {

	function go() {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'init', array( __CLASS__, 'setup_upgrade_filters' ) );
	}

	function setup_upgrade_filters() {
		$options = self::get_options();

		// Do these at priority 1, so other folks can easily override it.

		if ( ! $options['active'] ) {
			add_filter( 'auto_upgrader_disabled', '__return_true', 1 );
			return; // It's all disabled, no need to check the others.
		}

		if ( $options['core'] ) {
			add_filter( 'auto_upgrade_core', '__return_true', 1 );
		}

		if ( $options['plugin'] ) {
			add_filter( 'auto_upgrade_plugin', '__return_true', 1 );
		}

		if ( $options['theme'] ) {
			add_filter( 'auto_upgrade_theme', '__return_true', 1 );
		}
	}

	function get_options() {
		$defaults = array(
			'active'     => true,
			'core'       => false,
			'plugin'     => false,
			'theme'      => false,
		);
		$args = get_option( 'update_control_options', array() );
		return wp_parse_args( $args, $defaults );
	}

	function get_option( $key ) {
		$options = self::get_options();
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}
		return null;
	}

	function register_settings() {
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
			sprintf( '<label for="update_control_active">%1$s</label>', __( 'Permit Automatic Updates?', 'update-control' ) ),
			array( __CLASS__, 'update_control_active_cb' ),
			'general',
			'update-control'
		);

		add_settings_field(
			'update_control_core',
			sprintf( '<label for="update_control_core">%1$s</label>', __( 'Permit Automatic Major Core Updates?', 'update-control' ) ),
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

		register_setting( 'general', 'update_control_options', array( __CLASS__, 'sanitize_options' ) );
	}

	function update_control_settings_section() {
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
					$('#update_control_active').click(function(){
						$('.update_control_dependency').attr( 'disabled', ! this.checked );
					}).trigger('click');
				});
			</script>
			<style>
			.update_control_dependency[disabled] {
				opacity: 0.4;
			}
			</style>
		<?php endif;
	}

	function update_control_active_cb() {
		?>
		<input type="checkbox" id="update_control_active" name="update_control_options[active]" <?php checked( self::get_option( 'active' ) ); ?> />
		<?php
	}

	function update_control_core_cb() {
		?>
		<input type="checkbox" class="update_control_dependency" id="update_control_core" name="update_control_options[core]" <?php checked( self::get_option( 'core' ) ); ?> />
		<?php
	}

	function update_control_plugin_cb() {
		?>
		<input type="checkbox" class="update_control_dependency" id="update_control_plugin" name="update_control_options[plugin]" <?php checked( self::get_option( 'plugin' ) ); ?> />
		<?php
	}

	function update_control_theme_cb() {
		?>
		<input type="checkbox" class="update_control_dependency" id="update_control_theme" name="update_control_options[theme]" <?php checked( self::get_option( 'theme' ) ); ?> />
		<?php
	}

	function sanitize_options( $options ) {
		$options = (array) $options;

		$options['active'] = ! empty( $options['active'] );
		$options['core']   = ! empty( $options['core']   );
		$options['plugin'] = ! empty( $options['plugin'] );
		$options['theme']  = ! empty( $options['theme']  );

		return $options;
	}

}
Stephanis_Update_Control::go();