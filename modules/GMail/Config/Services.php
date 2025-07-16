<?php

namespace Modules\GMail\Config;

use CodeIgniter\Config\BaseService;
use Modules\GMail\Config\Email as EmailConfig;
use Modules\GMail\Email\Email;

class Services extends BaseService
{
	public static function email($config = null, bool $getShared = true)
	{
		if($getShared)
			return static::getSharedInstance('email', $config);

		if (empty($config) || (! is_array($config) && ! $config instanceof EmailConfig))
			$config = config(EmailConfig::class);

		return new Email($config);
	}
}