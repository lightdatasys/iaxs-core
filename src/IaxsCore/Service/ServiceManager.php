<?php
/**
 * Instance Access
 *
 * @link      http://mattlight.com
 * @copyright Copyright (c) 2012–2013 Matt Light
 * @license   LICENSE.txt
 * @package   IaxsCore_Service
 */
namespace IaxsCore\Service;



use IaxsCore\Entity\InstanceInterface;

use Zend\ServiceManager\ServiceManager as ZendServiceManager;



/**
 * Instance aware service manager.
 *
 * @category   IaxsCore
 * @package    IaxsCore_Service
 */
class ServiceManager
extends ZendServiceManager
implements
	InstanceAwareInterface
{
	/**
	 * The instance the service manager should provide services based on.
	 *
	 * @var InstanceInterface
	 */
	private $_instance;



	/**
	 * Setter for instance.
	 *
	 * @param InstanceInterface
	 * @return void
	 */
	public function setInstance(InstanceInterface $instance = null)
	{
		$this->_instance = $instance;
		return $this;
	}


	/**
	 * Getter for instance.
	 *
	 * @return InstanceInterface
	 */
	public function getInstance()
	{
		return $this->_instance;
	}
}
