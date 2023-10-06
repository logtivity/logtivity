<?php

class Logtivity_Check_For_New_Settings
{
	public function __construct()
	{
		add_action('after_setup_theme', [$this, 'maybeCheckForNewSettings']);
	}

	public function maybeCheckForNewSettings()
	{
		if (!$this->shouldCheckInWithApi()) {
			return;
		}

		$this->checkForNewSettings();
	}

	public function checkForNewSettings()
	{
		if ( ! function_exists( 'get_plugins' ) ) {
	        require_once ABSPATH . 'wp-admin/includes/plugin.php';
	    }

		update_option('logtivity_last_settings_check_in_at', ['date' => date("Y-m-d H:i:s")]);

		try {
			$api = new Logtivity_Api;

			$theme = wp_get_theme();

			global $wp_version;

			if (!function_exists('get_core_updates')) {
				require_once ABSPATH . 'wp-admin/includes/update.php';
			}
			
			$core_updates = get_core_updates();

			if (isset($core_updates[0]) && property_exists($core_updates[0], 'current')) {
				$latest_wp_version = $core_updates[0]->current;
				$latest_wp_min_php_version = $core_updates[0]->php_version;
				$latest_wp_min_mysql_version = $core_updates[0]->mysql_version;
			} else {
				$latest_wp_version = null;
				$latest_wp_min_php_version = null;
				$latest_wp_min_mysql_version = null;
			}

			$response = $api->post('/settings-check', [
				'php_version' => phpversion(),
				'plugins' => $this->getPluginsWithStatuses(),
				'theme_name' => $theme ? $theme->name : null,
				'theme_version' => $theme ? $theme->version : null,
				'themes' => $this->getThemesListWithStatuses(),
				'wordpress_version' => $wp_version,
				'latest_wp_version' => $latest_wp_version,
				'latest_wp_min_php_version' => $latest_wp_min_php_version,
				'latest_wp_min_mysql_version' => $latest_wp_min_mysql_version,
			]);

			if (empty($response)) {
				return;
			}
			
			$body = json_decode($response, true);

			$api->updateSettings($body);
		} catch (\Exception $e) {
			
		}
	}

	private function getThemesListWithStatuses()
	{
		$themes = wp_get_themes();

		$themesDetails = [];

		foreach ($themes as $theme) {
			$themesDetails[] = [
				'name' => $theme->name,
				'version' => $theme->version,
			];
		}

		return $themesDetails;
	}

	private function getPluginsWithStatuses()
	{
		$allPlugins = get_plugins();
		$activePlugins = get_option('active_plugins');
		$pluginsWithUpdateAvailable = get_site_transient('update_plugins');

		$pluginData = [];

		foreach ($allPlugins as $filePath => $data) {

			$pluginData[$filePath]['Name'] = $data['Name'];
			$pluginData[$filePath]['Version'] = $data['Version'];
			$pluginData[$filePath]['Author'] = $data['Author'];
			$pluginData[$filePath]['AuthorURI'] = $data['AuthorURI'];
			$pluginData[$filePath]['slug'] = $filePath;

			if (in_array($filePath, $activePlugins)) {
				$pluginData[$filePath]['is_active'] = true;
			} else {
				$pluginData[$filePath]['is_active'] = false;
			}
			
			if (!empty($pluginsWithUpdateAvailable->response)) {
				if (array_key_exists($filePath, $pluginsWithUpdateAvailable->response)) {
					$pluginData[$filePath]['update_available'] = true;
					$pluginData[$filePath]['new_version'] = $this->getPropertyIfExists(
						$pluginsWithUpdateAvailable->response[$filePath],
						'new_version'
					);

					// if (
					// 	property_exists($pluginsWithUpdateAvailable->response[$filePath], 'icons')
					// 	&& is_array($pluginsWithUpdateAvailable->response[$filePath]->icons)
					// ) {
					// 	$allPlugins[$filePath]['icon'] = $this->getIcon($pluginsWithUpdateAvailable->response[$filePath]->icons);
					// }

					$pluginData[$filePath]['new_version_requires_wp'] = $this->getPropertyIfExists(
						$pluginsWithUpdateAvailable->response[$filePath],
						'requires'
					);
					$pluginData[$filePath]['tested'] = $this->getPropertyIfExists(
						$pluginsWithUpdateAvailable->response[$filePath],
						'tested'
					);
					$pluginData[$filePath]['new_version_requires_php'] = $this->getPropertyIfExists(
						$pluginsWithUpdateAvailable->response[$filePath],
						'requires_php'
					);
					// $pluginData[$filePath]['upgrade_notice'] = $this->getPropertyIfExists(
					// 	$pluginsWithUpdateAvailable->response[$filePath],
					// 	'upgrade_notice'
					// );
				} else {
					$pluginData[$filePath]['update_available'] = false;
					$pluginData[$filePath]['new_version'] = null;
					// $pluginData[$filePath]['icon'] = null;
					$pluginData[$filePath]['new_version_requires_wp'] = null;
					$pluginData[$filePath]['tested'] = null;
					$pluginData[$filePath]['new_version_requires_php'] = null;
					// $pluginData[$filePath]['upgrade_notice'] = null;
				}
			}
		}

		return $pluginData;
	}

	private function getPropertyIfExists($object, $property)
	{
		if (property_exists($object, $property)) {
			return $object->{$property};
		}
	}

	private function getIcon($icons)
	{
		foreach ($icons as $icon) {
			return $icon;
		}
	}

	public function shouldCheckInWithApi()
	{
		$latestReponse = get_option('logtivity_last_settings_check_in_at');

		if (is_array($latestReponse) && isset($latestReponse['date'])) {
			return time() - strtotime($latestReponse['date']) > 60 * MINUTE_IN_SECONDS; // 60 minutes
		}

		return true;
	}
}

$Logtivity_Check_For_New_Settings = new Logtivity_Check_For_New_Settings;