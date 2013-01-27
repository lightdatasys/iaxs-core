<?php
namespace IaxsCore\Controller\Plugin;



use PHPUnit_Framework_TestCase;

use IaxsCore\Controller\Event\IteratorEvent;
use IaxsCore\Entity\InstanceIterator\ArrayInstanceIterator;
use IaxsCore\Service\ServiceManager;

use Zend\EventManager\EventManager;



class InstanceIteratorTest
extends PHPUnit_Framework_TestCase
{
	protected $_plugin;
	protected $_event_manager;
	protected $_service_locator;
	protected $_controller;



	protected function setUp()
	{
		$this->_plugin          = new InstanceIterator();
		$this->_event_manager   = new EventManager();
		$this->_service_locator = $this
			->getMock('\Zend\ServiceManager\ServiceManager');
		$this->_controller      = $this
			->getMock('\Zend\Mvc\Controller\AbstractController');

		$this->_plugin->setEventManager($this->_event_manager);
		$this->_plugin->setServiceLocator($this->_service_locator);
		$this->_plugin->setController($this->_controller);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::getEventManager
	 * @expectedException \IaxsCore\Exception\NullPointerException
	 */
	public function testRetrievingEventManagerBeforeItIsProvidedCausesAnException()
	{
		$plugin = new InstanceIterator();
		$plugin->getEventManager();
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::getEventManager
	 */
	public function testEventManagerCanBeRetrievedFromController()
	{
		$this->_controller
			->expects($this->any())
			->method('getEventManager')
			->will($this->returnValue($this->_event_manager));
		$plugin = new InstanceIterator();
		$plugin->setController($this->_controller);

		$this->assertSame(
			$this->_event_manager,
			$plugin->getEventManager()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::setEventManager
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::getEventManager
	 */
	public function testPluginStoresEventManagerProperly()
	{
		$plugin = new InstanceIterator();
		$plugin->setEventManager($this->_event_manager);
		$this->assertSame(
			$this->_event_manager,
			$plugin->getEventManager()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::getServiceLocator
	 * @expectedException \IaxsCore\Exception\NullPointerException
	 */
	public function testRetrievingServiceLocatorBeforeItIsProvidedCausesAnException()
	{
		$plugin = new InstanceIterator();
		$plugin->getServiceLocator();
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::getServiceLocator
	 */
	public function testServiceLocatorCanBeRetrievedFromController()
	{
		$this->_controller
			->expects($this->any())
			->method('getServiceLocator')
			->will($this->returnValue($this->_service_locator));
		$plugin = new InstanceIterator();
		$plugin->setController($this->_controller);

		$this->assertSame(
			$this->_service_locator,
			$plugin->getServiceLocator()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::setServiceLocator
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::getServiceLocator
	 */
	public function testPluginStoresServiceLocatorProperly()
	{
		$plugin = new InstanceIterator();
		$plugin->setServiceLocator($this->_service_locator);
		$this->assertSame(
			$this->_service_locator,
			$plugin->getServiceLocator()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginTriggersIterateEventsInOrder()
	{
		$event_manager = $this->getMock('\Zend\EventManager\EventManager');
		$event_manager
			->expects($this->at(0))
			->method('trigger')
			->with(
				$this->equalTo(IteratorEvent::EVENT_ITERATION_PRE),
				$this->anything()
			);
		$event_manager
			->expects($this->at(1))
			->method('trigger')
			->with(
				$this->equalTo(IteratorEvent::EVENT_ITERATION),
				$this->anything()
			);
		$event_manager
			->expects($this->at(2))
			->method('trigger')
			->with(
				$this->equalTo(IteratorEvent::EVENT_ITERATION_POST),
				$this->anything()
			);
		$event_manager
			->expects($this->at(3))
			->method('trigger')
			->with(
				$this->equalTo(IteratorEvent::EVENT_ITERATION_PRE),
				$this->anything()
			);
		$event_manager
			->expects($this->at(4))
			->method('trigger')
			->with(
				$this->equalTo(IteratorEvent::EVENT_ITERATION),
				$this->anything()
			);
		$event_manager
			->expects($this->at(5))
			->method('trigger')
			->with(
				$this->equalTo(IteratorEvent::EVENT_ITERATION_POST),
				$this->anything()
			);
		$this->_plugin->setEventManager($event_manager);

		$this->_plugin->iterate(new ArrayInstanceIterator(array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		)));
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginTriggersEachEventWithTheSameEventObject()
	{
		$test_case      = $this;

		$last_event     = null;
		$listener       = function($event) use (&$last_event, $test_case) {
			if(null === $last_event) {
				$last_event = $event;
			}
			else {
				$test_case->assertSame(
					$last_event,
					$event
				);
			}
		};
		$this->_event_manager->attach(IteratorEvent::EVENT_ITERATION_PRE,  $listener);
		$this->_event_manager->attach(IteratorEvent::EVENT_ITERATION,      $listener);
		$this->_event_manager->attach(IteratorEvent::EVENT_ITERATION_POST, $listener);

		$instances      = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(new ArrayInstanceIterator($instances));
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 * @expectedException \IaxsCore\Exception\InvalidArgumentException
	 */
	public function testPassingInvalidCallableToIterateCausesAnException()
	{
		$this->_plugin->iterate(
			new ArrayInstanceIterator(array()),
			null,
			1
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginCallableIsCalledDuringIteration()
	{
		$test_case      = $this;

		$instances      = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			null,
			function($event) use ($test_case) {
				$test_case->assertSame(
					IteratorEvent::EVENT_ITERATION,
					$event->getName()
				);
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginCallableIsDetachedAfterIteration()
	{
		$instances      = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);
		$count          = 0;

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			null,
			function($event) use (&$count) {
				++$count;
			}
		);

		// iterate the instances again but without the callback
		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances)
		);

		$this->assertSame(
			2,
			$count
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginSetsEventInstanceProperly()
	{
		$test_case      = $this;

		$instances      = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			null,
			function($event) use (&$instances, $test_case) {
				$test_case->assertSame(
					array_shift($instances),
					$event->getInstance()
				);
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginSetsEventDefaultServiceLocatorProperly()
	{
		$test_case       = $this;
		$service_locator = $this->_service_locator;

		$instances      = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			null,
			function($event) use ($service_locator, $test_case) {
				$test_case->assertSame(
					$service_locator,
					$event->getDefaultServiceLocator()
				);
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginProvidesNullWhenContextualServiceLocatorServiceNameIsNotProvided()
	{
		$test_case       = $this;

		$instances       = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			null,
			function($event) use ($test_case) {
				$test_case->assertNull($event->getContextualServiceLocator());
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginUsesContextualServiceLocatorServiceNameWhenProvided()
	{
		$isl_service_name = 'iaxs_isl_service';
		$isl_service      = $this->getMock('\Zend\ServiceManager\ServiceManager');

		$test_case        = $this;
		$service_locator  = $this->_service_locator;
		$service_locator
			->expects($this->once())
			->method('get')
			->with($this->equalTo($isl_service_name))
			->will($this->returnValue($isl_service));

		$instances       = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			$isl_service_name,
			function($event) use ($isl_service, $test_case) {
				$test_case->assertSame(
					$isl_service,
					$event->getContextualServiceLocator()
				);
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginAccountsForUnsharedContextualServiceLocatorServices()
	{
		$isl_service_name = 'iaxs_isl_service';
		$isl_services     = array(
			$this->getMock('\Zend\ServiceManager\ServiceManager'),
			$this->getMock('\Zend\ServiceManager\ServiceManager'),
		);

		$test_case        = $this;
		$service_locator  = $this->_service_locator;
		$service_locator
			->expects($this->exactly(2))
			->method('get')
			->with($this->equalTo($isl_service_name))
			->will($this->onConsecutiveCalls(
				$isl_services[0],
				$isl_services[1]
			));

		$instances       = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			$isl_service_name,
			function($event) use (&$isl_services, $test_case) {
				$test_case->assertSame(
					array_shift($isl_services),
					$event->getContextualServiceLocator()
				);
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginProperlyGivesServiceTheInstanceWhenServiceIsInstanceAware()
	{
		$isl_service_name = 'iaxs_isl_service';
		$isl_service      = new ServiceManager();

		$test_case        = $this;
		$service_locator  = $this->_service_locator;
		$service_locator
			->expects($this->once())
			->method('get')
			->with($this->equalTo($isl_service_name))
			->will($this->returnValue($isl_service));

		$instances       = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			$isl_service_name,
			function($event) use ($test_case) {
				$test_case->assertSame(
					$event->getInstance(),
					$event->getContextualServiceLocator()->getInstance()
				);
			}
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Plugin\InstanceIterator::iterate
	 */
	public function testPluginUnsetsTheInstanceInTheServiceWhenServiceIsInstanceAware()
	{
		$isl_service_name = 'iaxs_isl_service';
		$isl_service      = new ServiceManager();

		$test_case        = $this;
		$service_locator  = $this->_service_locator;
		$service_locator
			->expects($this->once())
			->method('get')
			->with($this->equalTo($isl_service_name))
			->will($this->returnValue($isl_service));

		$instances       = array(
			$this->getMock('\IaxsCore\Entity\InstanceInterface'),
		);

		$this->_plugin->iterate(
			new ArrayInstanceIterator($instances),
			$isl_service_name
		);

		$this->assertNull($isl_service->getInstance());
	}
}