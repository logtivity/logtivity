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
			$array = explode('&&', $log);

			if (isset($array[0]) && isset($array[1])) {
			    if ($this->matches($Logtivity_Logger->action, $array[0]) && $this->matches($Logtivity_Logger->context, $array[1])) {
			        $Logtivity_Logger->stop();
			    }
			} elseif(isset($array[0])) {
				if ($this->matches($Logtivity_Logger->action, $array[0])) {
			        $Logtivity_Logger->stop();
			    }
			}
		}
	}

	private function matches($keyword1, $keyword2)
	{
		return strpos(trim($keyword1), trim($keyword2)) !== false;
	}

	private function getLogsToExclude()
	{
		$value = (new Logtivity_Options)->getOption('logtivity_disable_individual_logs');

		if ($value == '') {
			return [];
		}

		return preg_split("/\\r\\n|\\r|\\n/", $value);
	}
}

$CheckForDisabledIndividualLogs = new Logtivity_Check_For_Disabled_Individual_Logs;