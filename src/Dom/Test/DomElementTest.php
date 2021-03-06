<?php
/**
 * Part of Windwalker project Test files.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT Taiwan, Inc. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Dom\Test;

use Windwalker\Dom\DomElement;
use Windwalker\Dom\Helper\DomHelper;
use Windwalker\Dom\Test\AbstractDomTestCase;

/**
 * Test class of DomElement
 *
 * @since 2.0
 */
class DomElementTest extends AbstractDomTestCase
{
	/**
	 * domTestCase
	 *
	 * @return  array
	 */
	public function domTestCase()
	{
		return [
			[
				'case1',
				'<field />',
				'field',
				null,
				[],
				false
			],
			[
				'case2',
				'<field>Some Data</field>',
				'field',
				'Some Data',
				[],
				false
			],
			[
				'case3',
				'<field id="foo" class="bar" />',
				'field',
				null,
				['id' => 'foo', 'class' => 'bar'],
				false
			],
			[
				'case4',
				'<field id="foo" class="bar">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</field>',
				'field',
				[
					new DomElement('option', 'Yes', ['value' => 1]),
					new DomElement('option', 'No', ['value' => 0])
				],
				['id' => 'foo', 'class' => 'bar'],
				false
			],
			[
				'case5_force_paired',
				'<field></field>',
				'field',
				null,
				[],
				true
			],
		];
	}

	/**
	 * Method to test create().
	 *
	 * @param string  $name
	 * @param string  $expect
	 * @param string  $tag
	 * @param string  $content
	 * @param array   $attribs
	 * @param boolean $forcePaired
	 *
	 * @return void
	 *
	 * @covers       Windwalker\Dom\Builder\DomBuilder::create
	 *
	 * @dataProvider domTestCase
	 */
	public function testCreate($name, $expect, $tag, $content, $attribs, $forcePaired)
	{
		$element = new DomElement($tag, $content, $attribs);

		$this->assertDomFormatEquals(
			$expect,
			$element->toString($forcePaired),
			'Dom build case fail: ' . $name
		);
	}

	/**
	 * Method to test __toString().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::__toString
	 */
	public function test__toString()
	{
		$this->assertDomFormatEquals(
			'<field id="foo">data</field>',
			new DomElement('field', 'data', ['id' => 'foo'])
		);
	}

	/**
	 * Method to test getContent().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::getContent
	 */
	public function testGetContent()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$this->assertEquals('data', $element->getContent());
	}

	/**
	 * Method to test setContent().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::setContent
	 */
	public function testSetContent()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$element->setContent('bar');

		$this->assertEquals(
			DomHelper::minify('<field id="foo">bar</field>'),
			DomHelper::minify($element)
		);
	}

	/**
	 * Method to test getAttribute().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::getAttribute
	 */
	public function testGetAttribute()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$this->assertEquals('foo', $element->getAttribute('id'));
	}

	/**
	 * Method to test setAttribute().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::setAttribute
	 */
	public function testSetAttribute()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$element->setAttribute('id', 'bar');
		$element->setAttribute('class', 'yoo');

		$this->assertEquals(
			DomHelper::minify('<field id="bar" class="yoo">data</field>'),
			DomHelper::minify($element)
		);
	}

	/**
	 * Method to test getAttributes().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::getAttributes
	 */
	public function testGetAttributes()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$this->assertEquals(['id' => 'foo'], $element->getAttributes());
	}

	/**
	 * Method to test setAttributes().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::setAttributes
	 */
	public function testSetAttributes()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$element->setAttributes(['a' => 'b']);

		$this->assertEquals(['a' => 'b'], $element->getAttributes());

		$this->assertEquals(
			DomHelper::minify('<field a="b">data</field>'),
			DomHelper::minify($element)
		);
	}

	/**
	 * Method to test getName().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::getName
	 */
	public function testGetName()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$this->assertEquals('field', $element->getName());
	}

	/**
	 * Method to test setName().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::setName
	 */
	public function testSetName()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$element->setName('div');

		$this->assertEquals('div', $element->getName());

		$this->assertEquals(
			DomHelper::minify('<div id="foo">data</div>'),
			DomHelper::minify($element)
		);
	}

	/**
	 * Method to test offsetExists().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::offsetExists
	 */
	public function testOffsetExists()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$this->assertTrue(isset($element['id']));
		$this->assertFalse(isset($element['class']));
	}

	/**
	 * Method to test offsetGet().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::offsetGet
	 */
	public function testOffsetGet()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$this->assertEquals('foo', $element['id']);
	}

	/**
	 * Method to test offsetSet().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::offsetSet
	 */
	public function testOffsetSet()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		$element['id'] = 'bar';
		$element['class'] = 'yoo';

		$this->assertEquals(
			DomHelper::minify('<field id="bar" class="yoo">data</field>'),
			DomHelper::minify($element)
		);
	}

	/**
	 * Method to test offsetUnset().
	 *
	 * @return void
	 *
	 * @covers \Windwalker\Dom\DomElement::offsetUnset
	 */
	public function testOffsetUnset()
	{
		$element = new DomElement('field', 'data', ['id' => 'foo']);

		unset($element['id']);

		$this->assertEquals(
			DomHelper::minify('<field>data</field>'),
			DomHelper::minify($element)
		);
	}
}
