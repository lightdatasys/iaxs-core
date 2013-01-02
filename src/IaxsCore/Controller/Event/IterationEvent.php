<?php
/**
 * Instance Access
 *
 * @link      http://mattlight.com
 * @copyright Copyright (c) 2012–2013 Matt Light
 * @license   LICENSE.txt
 * @package   IaxsCore_Controller
 */
namespace IaxsCore\Controller\Event;



use IaxsCore\Entity\InstanceInterface;

use Zend\EventManager\Event;
use Zend\Mvc\Controller\AbstractController;
use Zend\ServiceManager\ServiceLocatorInterface;



/**
 * Event triggered during each iteration through an instance iterator.
 *
 * @category   IaxsCore
 * @package    IaxsCore_Controller
 * @subpackage Event
 */
class IterationEvent
extends Event
{
	const EVENT_ITERATION_PRE     = 'iaxs.core.iterate.pre';
	const EVENT_ITERATION         = 'iaxs.core.iterate';
	const EVENT_ITERATION_POST    = 'iaxs.core.iterate.post';



	/**
	 * Setter for controller.
	 *
	 * @param AbstractController
	 * @return void
	 */
	public function setController(AbstractController $controller)
	{
		$this->setParam('controller', $controller);
		return $this;
	}


	/**
	 * Getter for controller.
	 *
	 * @return AbstractController
	 */
	public function getController()
	{
		return $this->getParam('controller');
	}



	/**
	 * Setter for instance.
	 *
	 * @param InstanceInterface
	 * @return void
	 */
	public function setInstance(InstanceInterface $instance)
	{
		$this->setParam('instance', $instance);
		return $this;
	}


	/**
	 * Getter for instance.
	 *
	 * @return InstanceInterface
	 */
	public function getInstance()
	{
		return $this->getParam('instance');
	}



	/**
	 * Setter for default service locator.
	 *
	 * @param ServiceLocatorInterface
	 * @return void
	 */
	public function setDefaultServiceLocator(ServiceLocatorInterface $service_locator)
	{
		$this->setParam('service_locator_default', $service_locator);
		return $this;
	}


	/**
	 * Getter for default service locator.
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getDefaultServiceLocator()
	{
		return $this->getParam('service_locator_default');
	}



	/**
	 * Setter for contextual service locator.
	 *
	 * @param ServiceLocatorInterface
	 * @return void
	 */
	public function setContextualServiceLocator(ServiceLocatorInterface $service_locator = null)
	{
		$this->setParam('service_locator_contextual', $service_locator);
		return $this;
	}


	/**
	 * Getter for contextual service locator.
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getContextualServiceLocator()
	{
		return $this->getParam('service_locator_contextual');
	}
}
