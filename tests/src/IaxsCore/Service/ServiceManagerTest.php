<?php
namespace IaxsCore\Service;



use PHPUnit_Framework_TestCase;



class ServiceManagerTest
extends PHPUnit_Framework_TestCase
{
	protected $_service_manager;
	protected $_instance;



	protected function setUp()
	{
		$this->_service_manager = new ServiceManager();
		$this->_instance        = $this
			->getMock('\IaxsCore\Entity\InstanceInterface');;
	}



	/**
	 * @covers \IaxsCore\Service\ServiceManager
	 */
	public function testServiceManagerEventIsZendServiceManager()
	{
		$this->assertInstanceOf(
			'\Zend\ServiceManager\ServiceManager',
			$this->_service_manager
		);
	}



	/**
	 * @covers \IaxsCore\Service\ServiceManager::setInstance
	 * @covers \IaxsCore\Service\ServiceManager::getInstance
	 */
	public function testServiceManagerStoresInstanceProperly()
	{
		$this->_service_manager->setInstance($this->_instance);
		$this->assertSame(
			$this->_instance,
			$this->_service_manager->getInstance()
		);
	}
}