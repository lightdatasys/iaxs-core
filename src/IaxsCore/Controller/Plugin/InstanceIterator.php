<?php
/**
 * Instance Access
 *
 * @link      http://mattlight.com
 * @copyright Copyright (c) 2012–2013 Matt Light
 * @license   LICENSE.txt
 * @package   IaxsCore_Controller
 */
namespace IaxsCore\Controller\Plugin;



use IaxsCore\Controller\Event\IteratorEvent;
use IaxsCore\Entity\InstanceInterface;
use IaxsCore\Entity\InstanceIterator\InstanceIteratorInterface;
use IaxsCore\Exception;
use IaxsCore\Service\InstanceAwareInterface;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



/**
 * Controller plugin that triggers iteration events for each instance in the
 * instance iterator.
 *
 * @category   IaxsCore
 * @package    IaxsCore_Controller
 * @subpackage Plugin
 */
class InstanceIterator
extends AbstractPlugin
{
	/**
	 * The event manager to trigger the events on.
	 *
	 * @var Zend\EventManager\EventManagerInterface
	 */
	protected $_event_manager;

	/**
	 * The service locator to pass into or use to locate another service locator
	 * to pass into the event.
	 *
	 * @var Zend\ServiceManager\ServiceLocatorInterface
	 */
	protected $_service_locator;



	/**
	 * Triggers the iteration events for each instance in the instance iterator,
	 * passing the instance and the provided service locator to the event.
	 *
	 * @param InstanceIteratorInterface $iterator - the iterator to iterate
	 * @param string|null $context_service_locator_service_name - the name of
	 *          the service that holds the service locator specific to the
	 *          context of the type of instance being iterated on
	 * @param callable $callable - a function to add to and remove from the
	 *          'iterate' event before and after iterating, respectively
	 * @return void
	 */
	public function iterate(
		InstanceIteratorInterface $iterator, 
		$context_service_locator_service_name = null,
		$callable = null
	)
	{
		if($callable && !is_callable($callable)) {
			throw new Exception\InvalidArgumentException("Invalid callback provided as 'callable' parameter.");
		}

		$event_manager   = $this->getEventManager();
		$service_locator = $this->getServiceLocator();

		$event           = new IteratorEvent();
		$event->setTarget($this);
		$event->setController($this->getController());
		$event->setDefaultServiceLocator($service_locator);

		$callback_handler = null;
		if($callable) {
			$callback_handler = $event_manager->attach(IteratorEvent::EVENT_ITERATION, $callable);
		}

		$event_manager->trigger(IteratorEvent::EVENT_ITERATE_PRE,  $event);

		foreach($iterator as $instance) {
			$event->setInstance($instance);

			$contextual_service_locator = (
				$context_service_locator_service_name
				?   $service_locator->get($context_service_locator_service_name)
				:   null
			);
			$event->setContextualServiceLocator($contextual_service_locator);

			if($contextual_service_locator instanceof InstanceAwareInterface) {
				$contextual_service_locator->setInstance($instance);
			}

			$event_manager->trigger(IteratorEvent::EVENT_ITERATION_PRE,  $event);
			$event_manager->trigger(IteratorEvent::EVENT_ITERATION,      $event);
			$event_manager->trigger(IteratorEvent::EVENT_ITERATION_POST, $event);

			// the service shouldn't be associated with the instance once the
			// iteration is over
			if($contextual_service_locator instanceof InstanceAwareInterface) {
				$contextual_service_locator->setInstance(null);
			}
		}

		$event_manager->trigger(IteratorEvent::EVENT_ITERATE_POST,  $event);

		if($callback_handler) {
			$event_manager->detach($callback_handler);
		}
	}



	/**
	 * Setter for event manager.
	 *
	 * @param EventManagerInterface $event_manager
	 * @return void
	 */
	public function setEventManager(EventManagerInterface $event_manager)
	{
		$this->_event_manager = $event_manager;
	}


	/**
	 * Getter for event manager.
	 *
	 * @return EventManagerInterface
	 */
	public function getEventManager()
	{
		if(null === $this->_event_manager
			&& $this->getController() instanceof EventManagerAwareInterface
		) {
			$this->_event_manager = $this->getController()->getEventManager();
		}

		if(null === $this->_event_manager) {
			throw new Exception\NullPointerException('Event manager not provided.');
		}

		return $this->_event_manager;
	}



	/**
	 * Setter for service locator
	 *
	 * @param ServiceLocatorInterface $service_locator
	 * @return void
	 */
	public function setServiceLocator(ServiceLocatorInterface $service_locator)
	{
		$this->_service_locator	= $service_locator;
	}


	/**
	 * Getter for service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator()
	{
		if(null === $this->_service_locator
			&& $this->getController() instanceof ServiceLocatorAwareInterface
		) {
			$this->_service_locator = $this->getController()->getServiceLocator();
		}

		if(null === $this->_service_locator) {
			throw new Exception\NullPointerException('Service locator not provided.');
		}

		return $this->_service_locator;
	}
}