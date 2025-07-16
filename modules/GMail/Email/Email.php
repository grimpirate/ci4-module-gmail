<?php

namespace Modules\GMail\Email;

use CodeIgniter\Email\Email as BaseEmail;
use CodeIgniter\HTTP\RedirectResponse;

use Google\Client;
use Google\Service\Gmail as GoogleService;
use Google_Service_Gmail_Message;

class Email extends BaseEmail
{
	protected Client $client;
	protected string $credentialsPath;
	protected string $tokenPath;

	public function __construct($config = null)
	{
		parent::__construct($config);

		$this->client = new Client();
		$this->client->setScopes(GoogleService::GMAIL_SEND);
		$this->client->setAuthConfig($this->credentialsPath);
		$this->client->setAccessType('offline');
	}

	private function token($code = null)
	{
		if(file_exists($this->tokenPath))
			$this->client->setAccessToken(json_decode(file_get_contents($this->tokenPath), true));

		if($this->client->isAccessTokenExpired())
		{
			$refresh = $this->client->getRefreshToken();

			if(!$refresh && empty($code)) return redirect()->to($this->client->createAuthUrl());

			$accessToken = empty($code)
				? $this->client->fetchAccessTokenWithRefreshToken($refresh)
				: $this->client->fetchAccessTokenWithAuthCode($code);

			$this->client->setAccessToken($accessToken);

			if (array_key_exists('error', $accessToken))
				throw new \Exception($accessToken['error']);

			file_put_contents($this->tokenPath, json_encode($accessToken));
		}

		return $this->client;
	}

	private function getSentMIMEMessage()
	{
		$this->send(false);
		
		return "{$this->headerStr}{$this->subject}{$this->finalBody}";
	}

	public function gmail($code = null)
	{
		$token = $this->token($code);

		if($token instanceof RedirectResponse)
			return $token;

		$email = new Google_Service_Gmail_Message();
		$email->setRaw(str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($this->getSentMIMEMessage())));

		$service = new GoogleService($this->client);

		$message = $service->users_messages->send('me', $email);

		return $message->getId();
	}
}