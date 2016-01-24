<?php
namespace Infinity;

use Ethereal\Config;
use Ethereal\Cache as Cache;
use Ethereal\Hooks as Hooks;

class AppConfig extends \Ethereal\Config
{

	protected $dir = __DIR__.'/Config/';
	protected $logger;

	public function setLogger(\Psr\Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
		$logger->addInfo('Loaded Config: '.json_encode($this->config));
	}
}
