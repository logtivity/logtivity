<?php

class Logtivity_Check_For_Disabled_Individual_Logs
{
	public function __construct()
	{
		add_action('wp_logtivity_instance', [$this, 'handle'], 10, 999);
	}

	public function handle($Logtivity_Logger)
	{
		foreach ($this->getLogsToExclude() as $log) {
			if ($this->check($Logtivity_Logger, $log)) {
				$Logtivity_Logger->stop();
			}
		}

		foreach ($this->globalLogsToExclude() as $log) {
			if ($this->check($Logtivity_Logger, $log)) {
				$Logtivity_Logger->stop();
			}
		}
	}

	public function check($Logtivity_Logger, $log)
	{
		$array = explode('&&', $log);

		if (isset($array[0]) && isset($array[1])) {
			if (trim($array[0]) === '*' && trim($array[1]) === '*') {
				return;
			}
		    if ($this->matches($Logtivity_Logger->action, $array[0]) && $this->matches($Logtivity_Logger->context, $array[1])) {
		        return true;
		    }
		} elseif(isset($array[0])) {
			if (trim($array[0]) === '*') {
				return;
			}
			if ($this->matches($Logtivity_Logger->action, $array[0])) {
		        return true;
		    }
		}
		return false;
	}

	private function matches($keyword1, $disabledKeyword)
	{
		$disabledKeyword = trim($disabledKeyword);

		if ($disabledKeyword === '*') {
			return true;
		}
		
		return strtolower(trim($keyword1)) == strtolower($disabledKeyword);
	}

	private function getLogsToExclude()
	{
		$value = (new Logtivity_Options)->getOption('logtivity_disable_individual_logs');

		if ($value == '') {
			return [];
		}

		return preg_split("/\\r\\n|\\r|\\n/", $value);
	}

	public function globalLogsToExclude()
	{
		$value = (new Logtivity_Options)->getOption('logtivity_global_disabled_logs');

		if ($value == '') {
			return [];
		}

		return preg_split("/\\r\\n|\\r|\\n/", $value);
	}
}

$CheckForDisabledIndividualLogs = new Logtivity_Check_For_Disabled_Individual_Logs;