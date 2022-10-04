<?php

class Logtivity_Stack_Trace
{
	private $stackTrace;
	
	private $files = [];

	public function __construct()
	{
		
	}

	public function createFromArray($stackTrace)
	{
		foreach ($stackTrace as $file) {
			if (isset($file['file']) && isset($file['line'])) {
				$this->files[] = $this->createFileObject(
					$file['file'],
					$file['line']
				);
			}
		}
		return $this->files;
	}

	public function createFromString($stackTrace)
	{
		$stackTrace = array_filter(
			preg_split('/\r\n|\r|\n/', $stackTrace)
		);

		foreach ($stackTrace as $file) {
			$array = explode(' ', $file);

			preg_match('#\((.*?)\)#', $array[1], $line);

			if (isset($line[1])) {
				$this->files[] = $this->createFileObject(
					str_replace('('.$line[1].'):', '', $array[1]),
					$line[1] ?? null
				);
			}
		}
		
		return $this->files;
	}

	public function createFileObject($filePath, $line)
	{
		return (object)[
			'file' => $filePath,
			'line' => $line,
			'code_snippet' => (new Logtivity_Stack_Trace_Snippet($filePath))->line($line)->get()
		];
	}
}