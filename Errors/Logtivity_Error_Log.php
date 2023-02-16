<?php

class Logtivity_Error_Log
{
	private $errorHandler;

	private $exceptionHandler;

	public function __construct()
	{
		$this->errorHandler = set_error_handler([$this, 'errorHandler']);

		$this->exceptionHandler = set_exception_handler([$this, 'exceptionHandler']);
	}

	public function errorHandler( $code, $message, $file = '', $line = 0 )
	{
		try {
			if (isset($_SERVER['HTTP_HOST'])) {
		        $stack_trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS & ~DEBUG_BACKTRACE_PROVIDE_OBJECT);
			} else {
				$stack_trace = [
					[
						'line' => $file,
						'file' => $line,
					]
				];
			}

			$error = [
				'type' => $code,
				'message' => $message,
				'file' => $file,
				'line' => $line,
				'stack_trace' => $stack_trace,
				'level' => 'warning',
			];

			if ($this->shouldIgnore($error, 'warnings')) {
				return;
			}

			Logtivity::logError($error)->send();
		} catch (\Throwable $e) {
		  
		} catch (\Exception $e) {
		  
		}

		if ($this->errorHandler) {
			return call_user_func($this->errorHandler, $code, $message, $file, $line);
		}
	}

	public function exceptionHandler(Throwable $throwable)
	{
		try {
			if (isset($_SERVER['HTTP_HOST'])) {
				$stack_trace = array_merge(
					[
						[
							'line' => $throwable->getLine(),
							'file' => $throwable->getFile(),
						]
					],
					$throwable->getTrace()
				);
			} else {
				$stack_trace = [
					[
						'line' => $throwable->getLine(),
						'file' => $throwable->getFile(),
					]
				];
			}

			$error = [
				'type' => get_class($throwable),
				'message' => $throwable->getMessage(),
				'file' => $throwable->getFile(),
				'line' => $throwable->getLine(),
				'stack_trace' => $stack_trace,
				'level' => 'error',
			];

			if ($this->shouldIgnore($error, 'errors')) {
				return;
			}
		
			Logtivity::logError($error)->send();
		} catch (\Throwable $e) {
		  
		} catch (\Exception $e) {
		  
		}

		if ($this->exceptionHandler) {
			call_user_func($this->exceptionHandler, $throwable);
		}
	}

	private function shouldIgnore($error, $type)
	{
		if (E_WARNING === $error['type'] && false !== strpos($error['message'], 'unlink')) {
			return true;
		}

		if ($this->loggingDisabled()) {
			return true;
		}

		if ($this->isErrorTypeDisabled($error, $type)) {
			return true;
		}

		if ($this->maybeRateLimit($error)) {
			return true;
		}

		return apply_filters('logtivity_should_ignore_error', false, $error);
	}

	public function maybeRateLimit($error)
	{
		if (!is_int($error['type'])) {
			return false;
		}

		if (in_array($error['type'], [E_ERROR, E_PARSE])) {
			return false;
		}

		$hash = md5($error['message']);

		if (false === ($transient = get_transient('logtivity_'.$hash))) {
			set_transient( 'logtivity_'.$hash, true, 24 * HOUR_IN_SECONDS );
			return false;
		}

		return true;
	}

	public function isErrorTypeDisabled($error, $type)
	{
		return in_array(
			$type,
			(new Logtivity_Options)->disabledErrorLevels()
		);
	}

	public function loggingDisabled()
	{
		return (new Logtivity_Options)->getOption('logtivity_disable_error_logging');
	}
}

$Logtivity_Error_Log = new Logtivity_Error_Log;