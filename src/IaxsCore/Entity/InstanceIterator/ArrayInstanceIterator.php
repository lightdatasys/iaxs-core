<?php
/**
 * Instance Access
 *
 * @link      http://mattlight.com
 * @copyright Copyright (c) 2012–2013 Matt Light
 * @license   LICENSE.txt
 * @package   IaxsCore_Entity
 */
namespace IaxsCore\Entity\InstanceIterator;



use ArrayIterator;



/**
 * Array implementation of an instance iterator
 *
 * Iterator that iterates through an array of InstanceInterface objects.
 *
 * @category   IaxsCore
 * @package    IaxsCore_Entity
 * @subpackage InstanceIterator
 */
class ArrayInstanceIterator
	extends ArrayIterator
	implements InstanceIteratorInterface
{
}