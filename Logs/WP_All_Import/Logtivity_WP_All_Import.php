<?php

class Logtivity_WP_All_Import
{
	protected $allowedAllImportLogs = [
		'All Import Running',
		'All Import Completed',
	];

	public function __construct()
	{
		if ($this->isImporting()) {
			$this->logImportButDisableIndividualChanges();
		}

		add_action('pmxi_before_xml_import', [$this, 'importRunning'], 10, 1);
		add_action('pmxi_after_xml_import', [$this, 'importCompleted'], 10, 2);
	}

	public function importRunning($import_id)
	{
		Logtivity_Logger::log()
			->setAction('All Import Running')
			->setContext($import_id)
			->addMeta('Import ID', $import_id)
			->send();
	}

	public function importCompleted($import_id, $import)
	{
		Logtivity_Logger::log()
			->setAction('All Import Completed')
			->setContext($import_id)
			->addMeta('Import ID', $import_id)
			->send();
	}

	public function isImporting()
	{
		// Manually running an import
		$manual_run = isset( $_GET['page'] ) && ( $_GET['page'] == 'pmxi-admin-import' || $_GET['page'] == 'pmxi-admin-manage' );

		// Cron jobs running an import
		$cron_run = isset( $_GET['import_id'] ) && isset( $_GET['action'] ) && $_GET['action'] == 'processing';

		// Automatic Scheduling service running an import
		$automatic_scheduling_run = ( isset( $_GET['action'] ) && $_GET['action'] == 'wpai_public_api' ) && ( isset( $_GET['q'] ) && $_GET['q'] == 'scheduling/process' );

		// Manually uploading an import file
		$manual_upload = ( isset($_GET['page']) && $_GET['page'] == 'pmxi-admin-settings' ) && ( isset( $_GET['action'] ) && $_GET['action'] == 'upload' );

		// Extra check: see if import ID is numeric
		$import_id = is_numeric( $this->wp_all_import_get_import_id() );

		// Check if they're importing
		if ( $manual_run || $cron_run || $automatic_scheduling_run || $manual_upload || $import_id ) {
			return true;
		}
	}

	public function logImportButDisableIndividualChanges()
	{
		add_action('wp_logtivity_instance', function($Logtivity_Logger) {
			if (!in_array($Logtivity_Logger->action, $this->allowedAllImportLogs)) {
				$Logtivity_Logger->stop();
			}
		});
	}

    public function wp_all_import_get_import_id() {
        global $argv;
        $import_id = 'new';
            
        if ( ! empty( $argv ) ) {
            $import_id_arr = array_filter( $argv, function( $a ) {
                return ( is_numeric( $a ) ) ? true : false;
            });
                
            if ( ! empty( $import_id_arr ) ) {
                $import_id = reset( $import_id_arr );
            }
        }
    
        if ( $import_id == 'new' ) {
            if ( isset( $_GET['import_id'] ) ) {
                $import_id = intval($_GET['import_id']);
            // } elseif ( isset( $_GET['id'] ) ) {
            //     $import_id = intval($_GET['id']);
            }
        }

        return $import_id;
    }
}

$Logtivity_WP_All_Import = new Logtivity_WP_All_Import;