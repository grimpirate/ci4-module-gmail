<?php

namespace Modules\GMail\Config;

use Config\Email as BaseEmailConfig;

class Email extends BaseEmailConfig
{
	public string $fromEmail       = 'jane@doe.com';
	public string $fromName        = 'Jane Doe';
	public string $protocol        = 'gmail';
	public string $mailType        = 'html';
	public string $charset         = 'UTF-8';

	public string $redirectUri     = 'http://localhost'; // Must match as specified in credentials.json
	public string $credentialsPath = __DIR__ . '/credentials.json';
	public string $tokenPath       = __DIR__ . '/token.json';
}