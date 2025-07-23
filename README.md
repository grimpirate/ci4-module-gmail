# Gmail
A CodeIgniter4 Module that provides email services via Gmail OAuth API rather than SMTP. Created to overcome [this issue](https://docs.digitalocean.com/support/why-is-smtp-blocked/).

## Setup
~
```
composer require google/apiclient
git clone --depth 1 --branch main --single-branch https://github.com/grimpirate/ci4-module-gmail
mv ci4-module-gmail/modules .
rm -rf ci4-module-gmail
```
modules/Gmail/Config/
```
credentials.json
```
app/Config/Autoload.php
```
public $psr4 = [
    'Modules\Gmail' => ROOTPATH . 'modules/Gmail',
];
```
app/Controllers/Home.php
```
<?php

namespace App\Controllers;

use Modules\Gmail\Exceptions\AuthorizationException;

class Home extends BaseController
{
	public function index()
	{
		$email = service('email');
		
		$title = 'HTML Email';
		$to = 'someone@somewhere.com';
		$fromName = $email->fromName;
		$fromEmail = $email->fromEmail;
		$data = compact('title', 'to', 'fromName', 'fromEmail');

		$email->setReplyTo($fromEmail, $fromName);
		$email->setTo($to);
		$email->setSubject($title);
		$email->setMessage(view('Modules\Gmail\Views\html\default', $data));
		$email->setAltMessage(view('Modules\Gmail\Views\text\default', $data));

		return $email->send() ? 'success' : 'failure';
	}

	public function get_oauth_token()
	{
		return service('email')->createAuthUrl();
	}
}
```
app/Config/Routes.php
```
$routes->get('/get_oauth_token', 'Home::get_oauth_token');
```
