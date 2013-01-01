<?php
/**
 * Instance Access
 *
 * @link      http://mattlight.com
 * @copyright Copyright (c) 2012–2013 Matt Light
 * @license   LICENSE.txt
 * @package   IaxsCore_Module
 */
namespace IaxsCore;



use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;



/**
 * Interface to be implemented by objects representing instances
 *
 * @category   IaxsCore
 * @package    IaxsCore_Module
 */
class Module
implements
	AutoloaderProviderInterface,
	ControllerPluginProviderInterface
{
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
		);
	}

	public function getControllerPluginConfig()
	{
		return array(
			'invokables' => array(
				'iaxsInstanceIterator' => 'IaxsCore\Controller\Plugin\InstanceIterator',
			),
		);
	}
}
