<?php

class Logtivity_Code_Snippets
{
	public function __construct()
	{
		add_action( 'code_snippets/delete_snippet', [$this, 'snippetDeleted'], 10, 2);
		add_action( 'code_snippets/create_snippet', [$this, 'snippetCreated'], 10, 2);
		add_action( 'code_snippets/update_snippet', [$this, 'snippetUpdated'], 10, 2);
	}

	public function snippetDeleted($id, $multisite)
	{
		Logtivity_Logger::log()
			->setAction('Code Snippet Deleted')
			->setContext($id)
			->addMeta('Multisite', $multisite)
			->send();
	}

	public function snippetCreated($id, $table)
	{
		return $this->logWithMeta(
			Logtivity_Logger::log()
				->setAction('Code Snippet Created')
				->setContext($id)
				->addMetaIf(
					isset($_GET['action']) && $_GET['action'] == 'clone',
					'Cloned from ID',
					$_GET['id'] ?? null
				)
		);
	}

	public function snippetUpdated($id, $table)
	{
		$this->logWithMeta(
			Logtivity_Logger::log()
				->setAction('Code Snippet Updated')
				->setContext($id)
		);
		
		if (isset($_POST['save_snippet_activate'])) {
			$this->snippetActivated($id);
		}

		if (isset($_POST['save_snippet_deactivate'])) {
			$this->snippetDeactivated($id);
		}
	}

	public function snippetActivated($id)
	{
		Logtivity_Logger::log()
			->setAction('Code Snippet Activated')
			->setContext($id)
			->send();
	}

	public function snippetDeactivated($id)
	{
		Logtivity_Logger::log()
			->setAction('Code Snippet Deactivated')
			->setContext($id)
			->send();
	}

	public function logWithMeta($logger)
	{
		return $logger
			->addMetaIf(
				isset($_POST['snippet_name']) && $_POST['snippet_name'] != '', 
				'Snippet Title', 
				$_POST['snippet_name']
			)
			->addMetaIf(
				isset($_POST['snippet_code']) && $_POST['snippet_code'] != '', 
				'Snippet Code', 
				$_POST['snippet_code']
			)
			->addMetaIf(
				isset($_POST['snippet_scope']) && $_POST['snippet_scope'] != '', 
				'Snippet Scope', 
				$_POST['snippet_scope']
			)
			->addMetaIf(
				isset($_POST['snippet_priority']) && $_POST['snippet_priority'] != '', 
				'Snippet Priority', 
				$_POST['snippet_priority']
			)
			->addMetaIf(
				isset($_POST['snippet_tags']) && $_POST['snippet_tags'] != '', 
				'Snippet Tags', 
				$_POST['snippet_tags']
			)
			->send();
	}
}

$Logtivity_Code_Snippets = new Logtivity_Code_Snippets;