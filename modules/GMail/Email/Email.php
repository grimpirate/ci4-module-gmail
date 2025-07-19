<?php

namespace Modules\GMail\Email;

use CodeIgniter\Email\Email as BaseEmail;

use Modules\GMail\Exceptions\AuthorizationException;
use Modules\GMail\Exceptions\TokenException;

use Google\Client;
use Google\Service\Gmail as GoogleService;
use Google_Service_Gmail_Message;

class Email extends BaseEmail
{
	protected $protocols = [
		'mail',
		'sendmail',
		'smtp',
		'gmail',
	];

	protected Client $client;
	protected string $redirectUri;
	public string $credentialsPath;
	$this->client->setRedirectUri($this->redirectUri);
	public string $tokenPath;

	public function __construct($config = null)
	{
		parent::__construct($config);

		$this->client = new Client();
		$this->client->setScopes(GoogleService::GMAIL_SEND);
		$this->client->setAuthConfig($this->credentialsPath);
		$this->client->setAccessType('offline');
	}

	public function createAuthUrl()
	{
		return redirect()->to($this->client->createAuthUrl());
	}

	private function getAuthToken()
	{
		if(file_exists($this->tokenPath))
			$this->client->setAccessToken(json_decode(file_get_contents($this->tokenPath), true));

		if($this->client->isAccessTokenExpired())
		{
			$refresh = $this->client->getRefreshToken();

			$accessToken = match(!$refresh)
			{
				true => (function(){
					$code = service('request')->getGet('code');
					if(empty($code))
						throw new AuthorizationException();
					return $this->client->fetchAccessTokenWithAuthCode($code);
				})(),
				default => $this->client->fetchAccessTokenWithRefreshToken($refresh),
			};

			if (array_key_exists('error', $accessToken))
				throw new TokenException($accessToken['error']);

			$this->client->setAccessToken($accessToken);

			file_put_contents($this->tokenPath, json_encode($accessToken));
		}

		return $this->client;
	}

	public function sendWithGmail()
	{
		$email = new Google_Service_Gmail_Message();
		$email->setRaw(str_replace(['+', '/', '='], ['-', '_', ''], base64_encode("{$this->headerStr}{$this->subject}{$this->finalBody}")));

		$service = new GoogleService($this->getAuthToken());

		return $service->users_messages->send('me', $email) ? true : false;
	}
}