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



/**
 * Interface to be implemented by objects aware of a particular instance
 *
 * @category   IaxsCore
 * @package    IaxsCore_Service
 */
interface InstanceAwareInterface
{
	/**
	 * Setter for instance.
	 *
	 * @param InstanceInterface
	 * @return void
	 */
	public function setInstance(InstanceInterface $instance = null);

	/**
	 * Getter for instance.
	 *
	 * @return InstanceInterface
	 */
	public function getInstance();
}