# Gmail
A CodeIgniter4 Module that provides email services via Gmail OAuth API rather than SMTP.

## Setup
~
```
composer require google/apiclient
git clone https://github.com/grimpirate/ci4-module-gmail
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
		$email->setReplyTo($email->fromEmail, $email->fromName);
		$email->setTo('someone@somewhere.com');
		$email->setSubject('HTML Email');
		$email->setMessage(view('Modules\Gmail\Views\html\default'));
		$email->setAltMessage(view('Modules\Gmail\Views\text\default'));
		$result = $email->send(false);
		if(!$result)
			return $email->printDebugger([]);
		return 'success';
	}

	public function gmail()
	{
		return service('email')->createAuthUrl();
	}
}
```
app/Config/Routes.php
```
$routes->get('/gmail', 'Home::gmail');
```