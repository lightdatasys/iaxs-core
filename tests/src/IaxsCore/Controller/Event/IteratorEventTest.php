<?php
namespace IaxsCore\Controller\Event;



use PHPUnit_Framework_TestCase;



class IteratorEventTest
extends PHPUnit_Framework_TestCase
{
	protected $_event;
	protected $_controller;
	protected $_instance;
	protected $_service_locator;



	protected function setUp()
	{
		$this->_event           = new IteratorEvent();
		$this->_controller      = $this
			->getMock('\Zend\Mvc\Controller\AbstractController');
		$this->_instance        = $this
			->getMock('\IaxsCore\Entity\InstanceInterface');
		$this->_service_locator = $this
			->getMock('\Zend\ServiceManager\ServiceManager');
	}



	/**
	 * @covers \IaxsCore\Controller\Event\IteratorEvent
	 */
	public function testIteratorEventIsZendEvent()
	{
		$this->assertInstanceOf(
			'\Zend\EventManager\Event',
			$this->_event
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::setController
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::getController
	 */
	public function testEventStoresControllerProperly()
	{
		$this->_event->setController($this->_controller);
		$this->assertSame(
			$this->_controller,
			$this->_event->getController()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::setInstance
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::getInstance
	 */
	public function testEventStoresInstanceProperly()
	{
		$this->_event->setInstance($this->_instance);
		$this->assertSame(
			$this->_instance,
			$this->_event->getInstance()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::setDefaultServiceLocator
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::getDefaultServiceLocator
	 */
	public function testEventStoresDefaultServiceLocatorProperly()
	{
		$this->_event->setDefaultServiceLocator($this->_service_locator);
		$this->assertSame(
			$this->_service_locator,
			$this->_event->getDefaultServiceLocator()
		);
	}



	/**
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::setContextualServiceLocator
	 * @covers \IaxsCore\Controller\Event\IteratorEvent::getContextualServiceLocator
	 */
	public function testEventStoresContextualServiceLocatorProperly()
	{
		$this->_event->setContextualServiceLocator($this->_service_locator);
		$this->assertSame(
			$this->_service_locator,
			$this->_event->getContextualServiceLocator()
		);
	}
}