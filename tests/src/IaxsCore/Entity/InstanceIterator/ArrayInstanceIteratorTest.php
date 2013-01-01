<?php
namespace IaxsCore\Entity\InstanceIterator;



use PHPUnit_Framework_TestCase;



class ArrayInstanceIteratorTest
extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers \IaxsCore\Entity\InstanceIterator\ArrayInstanceIterator
	 */
	public function testIteratorIsInstanceIterator()
	{
		$this->assertInstanceOf(
			'\IaxsCore\Entity\InstanceIterator\InstanceIteratorInterface',
			new ArrayInstanceIterator()
		);
	}
}