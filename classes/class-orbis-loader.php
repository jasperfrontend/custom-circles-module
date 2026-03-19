<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles loading the Orbis module when Beaver Builder is active.
 */
class Orbis_Loader {

	/**
	 * Boot the loader.
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'setup_hooks' ) );
	}

	/**
	 * Register hooks only when Beaver Builder is available.
	 */
	public static function setup_hooks() {
		if ( ! class_exists( 'FLBuilder' ) ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'load_modules' ) );
	}

	/**
	 * Load the Orbis module.
	 */
	public static function load_modules() {
		require_once ORBIS_DIR . 'modules/orbis/orbis.php';
	}
}

Orbis_Loader::init();
