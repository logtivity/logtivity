<?php

class Logtivity_Stack_Trace_Snippet
{
	private $file;
	
	private $line;

	private $lines = [];

	public function __construct($file)
	{
		$this->file = new SplFileObject($file);
	}

	public function line($line)
	{
		$this->line = $line;

		return $this;
	}

	public function getMaxLine()
	{
		$this->file->seek(PHP_INT_MAX);

		return $this->file->key() + 1;
	}

	public function get()
	{
		$maxLine = $this->getMaxLine();

		$line = (($this->line - 5) >= 1 ? ($this->line - 5) : 1);

		$end = (($this->line + 5) <= $maxLine ? ($this->line + 5) : $maxLine);

		while ($line <= $end) {
			$this->file->seek($line - 1);

			$this->lines[] = [
				'code' => $this->file->current(),
				'line' => $line,
			];

			$line++;
		}

		return $this->lines;
	}
}