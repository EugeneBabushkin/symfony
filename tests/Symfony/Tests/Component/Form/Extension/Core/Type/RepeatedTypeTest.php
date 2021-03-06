<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Component\Form\Extension\Core\Type;


class RepeatedTypeTest extends TypeTestCase
{
    protected $form;

    protected function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create('repeated', null, array(
            'type' => 'field',
        ));
        $this->form->setData(null);
    }

    public function testSetData()
    {
        $this->form->setData('foobar');

        $this->assertEquals('foobar', $this->form['first']->getData());
        $this->assertEquals('foobar', $this->form['second']->getData());
    }

    public function testSetOptions()
    {
        $form = $this->factory->create('repeated', null, array(
            'type'    => 'field',
            'options' => array('label' => 'Global'),
        ));

        $this->assertEquals('Global', $form['first']->getAttribute('label'));
        $this->assertEquals('Global', $form['second']->getAttribute('label'));
        $this->assertTrue($form['first']->isRequired());
        $this->assertTrue($form['second']->isRequired());
    }

    public function testSetOptionsPerField()
    {
        $form = $this->factory->create('repeated', null, array(
            // the global required value cannot be overriden
            'type'           => 'field',
            'first_options'  => array('label' => 'Test', 'required' => false),
            'second_options' => array('label' => 'Test2')
        ));

        $this->assertEquals('Test', $form['first']->getAttribute('label'));
        $this->assertEquals('Test2', $form['second']->getAttribute('label'));
        $this->assertTrue($form['first']->isRequired());
        $this->assertTrue($form['second']->isRequired());
    }

    public function testSetRequired()
    {
        $form = $this->factory->create('repeated', null, array(
            'required' => false,
            'type'     => 'field',
        ));

        $this->assertFalse($form['first']->isRequired());
        $this->assertFalse($form['second']->isRequired());
    }

    public function testSetOptionsPerFieldAndOverwrite()
    {
        $form = $this->factory->create('repeated', null, array(
            'type'           => 'field',
            'options'        => array('label' => 'Label'),
            'second_options' => array('label' => 'Second label')
        ));

        $this->assertEquals('Label', $form['first']->getAttribute('label'));
        $this->assertEquals('Second label', $form['second']->getAttribute('label'));
        $this->assertTrue($form['first']->isRequired());
        $this->assertTrue($form['second']->isRequired());
    }

    public function testSubmitUnequal()
    {
        $input = array('first' => 'foo', 'second' => 'bar');

        $this->form->bind($input);

        $this->assertEquals('foo', $this->form['first']->getClientData());
        $this->assertEquals('bar', $this->form['second']->getClientData());
        $this->assertFalse($this->form->isSynchronized());
        $this->assertEquals($input, $this->form->getClientData());
        $this->assertNull($this->form->getData());
    }

    public function testSubmitEqual()
    {
        $input = array('first' => 'foo', 'second' => 'foo');

        $this->form->bind($input);

        $this->assertEquals('foo', $this->form['first']->getClientData());
        $this->assertEquals('foo', $this->form['second']->getClientData());
        $this->assertTrue($this->form->isSynchronized());
        $this->assertEquals($input, $this->form->getClientData());
        $this->assertEquals('foo', $this->form->getData());
    }
}
