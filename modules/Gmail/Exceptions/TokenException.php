<?php

namespace Modules\Gmail\Exceptions;

class TokenException extends \ErrorException
{
	public function __construct($message = null, $code = 0, $severity = E_ERROR, $filename = null, $line = null, \Throwable $previous = null)
	{
		parent::__construct($message ?? lang('Email.noGmailToken'), $code, $severity, $filename, $line, $previous);
	}
}