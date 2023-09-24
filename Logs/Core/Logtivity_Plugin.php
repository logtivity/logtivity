<?php

class Logtivity_Plugin extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action( 'activated_plugin', [$this, 'pluginActivated'], 10, 2 );
		add_action( 'deactivated_plugin', [$this, 'pluginDeactivated'], 10, 2 );
		add_action( 'upgrader_process_complete', [$this, 'upgradeProcessComplete'], 10, 2);
		add_filter( 'editable_extensions', [$this, 'pluginFileModified'], 10, 2 );
		add_action( 'deleted_plugin', [$this, 'pluginDeleted'], 10, 2 );
	}

	public function pluginActivated($slug, $network_wide)
	{
		$data = get_plugin_data( WP_PLUGIN_DIR . '/' . $slug, true, false );

		return Logtivity_Logger::log()
			->setAction('Plugin Activated')
			->setContext($slug) 
			->addMeta('Slug', $slug)
			->addMeta('version', ( isset($data['Version']) ? $data['Version'] : 'Not set'))
			->addMeta('network_wide', $network_wide)
			->send();
	}

	public function pluginDeactivated($slug, $network_deactivating)
	{
		return Logtivity_Logger::log()
			->setAction('Plugin Deactivated')
			->setContext($slug)
			->addMeta('Slug', $slug)
			->addMeta('network_deactivating', $network_deactivating)
			->send();
	}

	public function pluginDeleted($plugin_file, $deleted)
	{
		return Logtivity_Logger::log()
					->setAction('Plugin Deleted')
					->setContext($plugin_file)
					->addMeta('Slug', $plugin_file)
					->addMeta('Deletion Successful', $deleted)
					->send();
	}

	public function upgradeProcessComplete( $upgrader_object, $options ) 
	{
	    if ( $options['type'] != 'plugin' ) {
	    	return;
	    }

		if ($options['action'] == 'update') {
			return $this->pluginUpdated($upgrader_object, $options);
		}

		if ($options['action'] == 'install') {
			return $this->pluginInstalled($upgrader_object, $options);
		}
	}

	public function pluginUpdated($upgrader_object, $options)
	{
		if ( isset( $options['bulk'] ) && true == $options['bulk'] ) {
			$slugs = $options['plugins'];
		} else {
			if ( ! isset( $upgrader->skin->plugin ) ) {
				return;
			}
			
			$slugs = array( $upgrader->skin->plugin );
		}
		
		foreach ( $slugs as $slug ) 
		{
			$data = get_plugin_data( WP_PLUGIN_DIR . '/' . $slug, true, false );
			
			Logtivity_Logger::log()
				->setAction('Plugin Updated')
				->setContext($data['Name'])
				->addMeta('Slug', $slug)
				->addMeta('Version', ( isset($data['Version']) ? $data['Version'] : 'Not set'))
				->addMeta('Bulk', $options['bulk'])
				->send();
		}

	}

	public function pluginInstalled($upgrader_object, $options)
	{
		$path = $upgrader_object->plugin_info();
		
		if ( ! $path ) {
			return;
		}
		
		$data = get_plugin_data( $upgrader_object->skin->result['local_destination'] . '/' . $path, true, false );
		
		return Logtivity_Logger::log()
					->setAction('Plugin Installed')
					->setContext($data['Name'])
					->addMeta('Slug', $path)
					->addMeta('Version', $data['Version'])
					->send();
	}

	public function pluginFileModified( $editable_extensions, $plugin ) {

		if ( ! isset($_POST['action']) || $_POST['action'] != 'edit-theme-plugin-file' ) {
			return $editable_extensions;
		}

		if (!isset($_POST['plugin'])) {
			return $editable_extensions;
		}

		$log = Logtivity_Logger::log()->setAction('Plugin File Edited');

		if ( ! empty( $_REQUEST['file'] ) ) {

			$plugin_dir  = explode( '/', sanitize_text_field($_REQUEST['file'] ));
			$plugin_data = array_values( get_plugins( '/' . $plugin_dir[0] ) );
			$plugin_data = array_shift( $plugin_data );

			if ( ! empty( $_POST['file'] ) ) {
				$log->addMeta('File', sanitize_text_field($_POST['file']));
			}

			if ( isset($plugin_data['Name']) ) {
				$log->setContext($plugin_data['Name']);
			}

		}

		$log->send();

		return $editable_extensions;
	}

}

$Logtivity_Plugin = new Logtivity_Plugin;