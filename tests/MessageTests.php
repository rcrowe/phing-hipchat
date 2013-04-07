<?php

namespace rcrowe\Hippy\Tests;

use rcrowe\Hippy\PhingTask;
use ReflectionObject;
use rcrowe\Hippy\Message;

class MessageTests extends \PHPUnit_Framework_TestCase
{
    public function testSetMsg()
    {
        $task = new PhingTask;
        $task->setMsg('hello world');
        $task->setMessage('test 2');
        $task->setText('five spice');
        $task->setHtml('<a href="#">egg and spoon race</a>');

        $reflect  = new ReflectionObject($task);
        $property = $reflect->getProperty('msgStore');
        $property->setAccessible(true);
        $store = $property->getValue($task);

        $this->assertTrue(count($store) === 4);

        $this->assertEquals($store[0]['format'], Message::FORMAT_TEXT);
        $this->assertEquals($store[0]['msg'], 'hello world');

        $this->assertEquals($store[1]['format'], Message::FORMAT_TEXT);
        $this->assertEquals($store[1]['msg'], 'test 2');

        $this->assertEquals($store[2]['format'], Message::FORMAT_TEXT);
        $this->assertEquals($store[2]['msg'], 'five spice');

        $this->assertEquals($store[3]['format'], Message::FORMAT_HTML);
        $this->assertEquals($store[3]['msg'], '<a href="#">egg and spoon race</a>');
    }

    /**
     * @expectedException BuildException
     */
    public function testInvalidText()
    {
        $task = new PhingTask;
        $task->setText('');
    }

    /**
     * @expectedException BuildException
     */
    public function testInvalidHtml()
    {
        $task = new PhingTask;
        $task->setHtml('');
    }

    public function testQueue()
    {
        $task = new PhingTask;
        $this->assertEquals(get_class($task->createQueue()), 'rcrowe\Hippy\PhingTask');
    }
}