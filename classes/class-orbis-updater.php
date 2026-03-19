<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checks GitHub releases for plugin updates and integrates
 * with the WordPress plugin update system.
 */
class Orbis_GitHub_Updater {

	private $file;
	private $basename;
	private $plugin_data;
	private $github_repo = 'jasperfrontend/custom-circles-module';
	private $github_response;

	public function __construct( $file ) {
		$this->file     = $file;
		$this->basename = plugin_basename( $file );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
	}

	/**
	 * Lazy-load plugin header data.
	 */
	private function get_plugin_data() {
		if ( is_null( $this->plugin_data ) ) {
			$this->plugin_data = get_plugin_data( $this->file );
		}
	}

	/**
	 * Fetch the latest release from GitHub (cached for 12 hours).
	 */
	private function fetch_release() {
		if ( ! is_null( $this->github_response ) ) {
			return;
		}

		$cache_key = 'orbis_github_release';
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			$this->github_response = $cached;
			return;
		}

		$url      = "https://api.github.com/repos/{$this->github_repo}/releases/latest";
		$response = wp_remote_get( $url, array(
			'headers' => array(
				'Accept' => 'application/vnd.github.v3+json',
			),
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return;
		}

		$this->github_response = json_decode( wp_remote_retrieve_body( $response ) );
		set_transient( $cache_key, $this->github_response, 12 * HOUR_IN_SECONDS );
	}

	/**
	 * Find the .zip asset download URL from the release.
	 */
	private function get_zip_url() {
		if ( empty( $this->github_response->assets ) ) {
			return '';
		}

		foreach ( $this->github_response->assets as $asset ) {
			if ( substr( $asset->name, -4 ) === '.zip' ) {
				return $asset->browser_download_url;
			}
		}

		return '';
	}

	/**
	 * Inject an update entry when a newer version exists on GitHub.
	 */
	public function check_update( $transient ) {
		if ( ! isset( $transient->checked ) ) {
			return $transient;
		}

		$this->get_plugin_data();
		$this->fetch_release();

		if ( is_null( $this->github_response ) || empty( $this->github_response->tag_name ) ) {
			return $transient;
		}

		$github_version = ltrim( $this->github_response->tag_name, 'v' );

		if ( version_compare( $github_version, $this->plugin_data['Version'], '>' ) ) {
			$transient->response[ $this->basename ] = (object) array(
				'slug'        => dirname( $this->basename ),
				'new_version' => $github_version,
				'url'         => "https://github.com/{$this->github_repo}",
				'package'     => $this->get_zip_url(),
			);
		}

		return $transient;
	}

	/**
	 * Provide plugin info for the WordPress update details popup.
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action ) {
			return $result;
		}
		if ( ! isset( $args->slug ) || $args->slug !== dirname( $this->basename ) ) {
			return $result;
		}

		$this->get_plugin_data();
		$this->fetch_release();

		if ( is_null( $this->github_response ) ) {
			return $result;
		}

		return (object) array(
			'name'              => $this->plugin_data['Name'],
			'slug'              => dirname( $this->basename ),
			'version'           => ltrim( $this->github_response->tag_name, 'v' ),
			'author'            => $this->plugin_data['AuthorName'],
			'homepage'          => $this->plugin_data['PluginURI'],
			'short_description' => $this->plugin_data['Description'],
			'sections'          => array(
				'description' => $this->plugin_data['Description'],
				'changelog'   => isset( $this->github_response->body ) ? $this->github_response->body : '',
			),
			'download_link'     => $this->get_zip_url(),
		);
	}

	/**
	 * After install, move the extracted folder to the correct location.
	 */
	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;

		if ( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->basename ) {
			return $result;
		}

		$install_dir = plugin_dir_path( $this->file );
		$wp_filesystem->move( $result['destination'], $install_dir );
		$result['destination'] = $install_dir;

		activate_plugin( $this->basename );

		return $result;
	}
}
