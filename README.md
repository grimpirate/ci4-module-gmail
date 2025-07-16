# GMail
A CodeIgniter4 Module that provides email services via GMail OAuth API rather than SMTP.

## Setup
~
```
composer require google/apiclient
git clone https://github.com/grimpirate/ci4-module-gmail
mv ci4-module-gmail/modules .
rm -rf ci4-module-gmail
```
modules/GMail/Config/
```
credentials.json
```
app/Config/Autoload.php
```
public $psr4 = [
    'Modules\GMail' => ROOTPATH . 'modules/GMail',
];
```
app/Controllers/Home.php
```
<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$email = service('email');
		$email->setReplyTo($email->fromEmail, $email->fromName);
		$email->setTo('someone@somewhere.com');
		$email->setSubject('HTML Email');
		$email->setMessage(view('Modules\GMail\Views\html\default'));
		$email->setAltMessage(view('Modules\GMail\Views\text\default'));
		return $email->gmail($this->request->getGet('code'));
	}
}
```
