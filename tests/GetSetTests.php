<?php

namespace rcrowe\Hippy\Tests;

use Mockery as m;
use rcrowe\Hippy\PhingTask;
use rcrowe\Hippy\Message;

class GetSetTests extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testSetToken()
    {
        $task = new PhingTask;
        $task->setToken('123');

        $this->assertEquals($task->getToken(), '123');
    }

    /**
     * @expectedException BuildException
     */
    public function testTokenNotSet()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.token')->andReturn(null)->once();

        $task->setProject($project);
        $task->getToken();
    }

    public function testProjectSetToken()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.token')->andReturn('321')->once();
        $task->setProject($project);

        $this->assertEquals($task->getToken(), '321');
    }

    public function testSetRoom()
    {
        $task = new PhingTask;
        $task->setRoom('cog');

        $this->assertEquals($task->getRoom(), 'cog');
    }

    /**
     * @expectedException BuildException
     */
    public function testRoomNotSet()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.room')->andReturn(null)->once();

        $task->setProject($project);
        $task->getRoom();
    }

    public function testProjectSetRoom()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.room')->andReturn('hippy')->once();
        $task->setProject($project);

        $this->assertEquals($task->getRoom(), 'hippy');
    }

    public function testSetFrom()
    {
        $task = new PhingTask;
        $task->setFrom('rcrowe');

        $this->assertEquals($task->getFrom(), 'rcrowe');
    }

    /**
     * @expectedException BuildException
     */
    public function testFromNotSet()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.from')->andReturn(null)->once();

        $task->setProject($project);
        $task->getFrom();
    }

    public function testProjectSetFrom()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.from')->andReturn('Hubot')->once();
        $task->setProject($project);

        $this->assertEquals($task->getFrom(), 'Hubot');
    }

    public function testSetNotify()
    {
        $task = new PhingTask;
        $task->setNotify(true);

        $this->assertTrue($task->getNotify());
    }

    public function testNotifyDefault()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.notify')->andReturn(null)->once();
        $task->setProject($project);

        $this->assertFalse($task->getNotify());
    }

    public function testProjectSetNotify()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.notify')->andReturn(true)->once();
        $task->setProject($project);

        $this->assertTrue($task->getNotify());
    }

    public function testSetBackground()
    {
        $task = new PhingTask;
        $task->setBackground(Message::BACKGROUND_GREEN);

        $this->assertEquals($task->getBackground(), Message::BACKGROUND_GREEN);
    }

    public function testBackgroundDefault()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.background')->andReturn(null)->once();
        $task->setProject($project);

        $this->assertEquals($task->getBackground(), Message::BACKGROUND_YELLOW);
    }

    public function testProjectSetBackground()
    {
        $task = new PhingTask;

        $project = m::mock('Project');
        $project->shouldReceive('getProperty')->with('hipchat.background')->andReturn(Message::BACKGROUND_PURPLE)->once();
        $task->setProject($project);

        $this->assertEquals($task->getBackground(), Message::BACKGROUND_PURPLE);
    }
}
