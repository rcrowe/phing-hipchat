<?php

namespace rcrowe\Hippy\Tests;

use rcrowe\Hippy\PhingTask;
use rcrowe\Hippy\Transport\Guzzle;
use rcrowe\Hippy\Message;
use Mockery as m;
use ReflectionObject;

class SendTests extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testNoMessageSet()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->times(0);

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->andReturn($entity)->times(0);

        $transport = new Guzzle('123', 'hippy', 'rcrowe');
        $transport->setHttp($http);

        $task = new PhingTask($transport);

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.token')->andReturn('123')->once();
        $project->shouldReceive('getProperty')->with('hipchat.room')->andReturn('hippy')->once();
        $project->shouldReceive('getProperty')->with('hipchat.from')->andReturn('rcrowe')->once();
        $task->setProject($project);

        $task->main();
    }

    public function testSendTest()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        $data = array(
            'room_id'        => 'hippy',
            'from'           => 'rcrowe',
            'message'        => 'hello world',
            'message_format' => Message::FORMAT_TEXT,
            'notify'         => true,
            'color'          => Message::BACKGROUND_GREEN,
            'format'         => 'json',
        );
        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->with(
            'rooms/message?format=json&auth_token=123',
            array('Content-type' => 'application/x-www-form-urlencoded'),
            http_build_query($data)
        )->andReturn($entity)->once();

        $transport = new Guzzle('123', 'hippy', 'rcrowe');
        $transport->setHttp($http);

        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.notify')->andReturn(true)->once();
        $project->shouldReceive('getProperty')->with('hipchat.background')->andReturn(Message::BACKGROUND_GREEN)->once();
        $task->setProject($project);

        $task->setMsg($data['message']);
        $task->main($transport);
    }

    public function testSendHtml()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        $data = array(
            'room_id'        => 'hippy',
            'from'           => 'rcrowe',
            'message'        => '<a href="#">cog powered</a>',
            'message_format' => Message::FORMAT_HTML,
            'notify'         => true,
            'color'          => Message::BACKGROUND_GREEN,
            'format'         => 'json',
        );
        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->with(
            'rooms/message?format=json&auth_token=123',
            array('Content-type' => 'application/x-www-form-urlencoded'),
            http_build_query($data)
        )->andReturn($entity)->once();

        $transport = new Guzzle('123', 'hippy', 'rcrowe');
        $transport->setHttp($http);

        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.notify')->andReturn(true)->once();
        $project->shouldReceive('getProperty')->with('hipchat.background')->andReturn($data['color'])->once();
        $task->setProject($project);

        $task->setHtml($data['message']);
        $task->main($transport);
    }

    public function testSendQueue()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->twice();

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->andReturn($entity)->twice();

        $transport = new Guzzle('123', 'hippy', 'rcrowe');
        $transport->setHttp($http);

        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.notify')->andReturn(true)->twice();
        $project->shouldReceive('getProperty')->with('hipchat.background')->andReturn(Message::BACKGROUND_RANDOM)->twice();
        $task->setProject($project);

        $task->setText('test 1');
        $task->setHtml('test 2');
        $task->main($transport);
    }

    /**
     * @expectedException BuildException
     */
    public function testUnknownFormat()
    {
        $task = new PhingTask;

        $reflect  = new ReflectionObject($task);
        $property = $reflect->getProperty('msgStore');
        $property->setAccessible(true);
        $property->setValue($task, array(
            array(
                'format' => 'random',
                'msg'    => 'test',
            ),
        ));

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.token')->andReturn('123')->once();
        $project->shouldReceive('getProperty')->with('hipchat.room')->andReturn('hippy')->once();
        $project->shouldReceive('getProperty')->with('hipchat.from')->andReturn('rcrowe')->once();
        $project->shouldReceive('getProperty')->with('hipchat.notify')->andReturn(false)->once();
        $project->shouldReceive('getProperty')->with('hipchat.background')->andReturn(Message::BACKGROUND_GREEN)->once();
        $task->setProject($project);

        $task->main();
    }
}