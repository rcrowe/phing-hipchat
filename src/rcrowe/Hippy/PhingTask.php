<?php

namespace rcrowe\Hippy;

use Task;
use Project;
use BuildException;
use rcrowe\Hippy\Transport\Guzzle;
use rcrowe\Hippy\Client;
use rcrowe\Hippy\Message;
use rcrowe\Hippy\Queue;

class PhingTask extends Task
{
    protected $token;
    protected $room;
    protected $from;
    protected $notify;
    protected $background;
    protected $msgStore = array();

    public function getToken()
    {
        $token = (!empty($this->token)) ? $this->token : $this->getProject()->getProperty('hipchat.token');

        if (empty($token)) {
            throw new BuildException('API token is not set');
        }

        return $token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getRoom()
    {
        $room = (!empty($this->room)) ? $this->room : $this->getProject()->getProperty('hipchat.room');

        if (empty($room)) {
            throw new BuildException('Room is not set');
        }

        return $room;
    }

    public function setRoom($room)
    {
        $this->room = $room;
    }

    public function getFrom()
    {
        $from = (!empty($this->from)) ? $this->from : $this->getProject()->getProperty('hipchat.from');

        if (empty($from)) {
            throw new BuildException('From is not set');
        }

        return $from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function getNotify()
    {
        $notify = (!empty($this->notify)) ? $this->notify : $this->getProject()->getProperty('hipchat.notify');

        if (empty($notify)) {
            $notify = false;
        }

        return $notify;
    }

    public function setNotify($notify)
    {
        $this->notify = $notify;
    }

    public function getBackground()
    {
        $background = (!empty($this->background)) ? $this->background :
                                                    $this->getProject()->getProperty('hipchat.background');

        if (empty($background)) {
            $background = Message::BACKGROUND_YELLOW;
        }

        return $background;
    }

    public function setBackground($background)
    {
        $this->background = $background;
    }

    public function setMsg($msg)
    {
        $this->setText($msg);
    }

    public function setMessage($msg)
    {
        $this->setText($msg);
    }

    public function setText($msg)
    {
        if (empty($msg)) {
            throw new BuildException('Invalid message');
        }

        $this->msgStore[] = array(
            'format' => Message::FORMAT_TEXT,
            'msg'    => $msg,
        );
    }

    public function setHtml($msg)
    {
        if (empty($msg)) {
            throw new BuildException('Invalid message');
        }

        $this->msgStore[] = array(
            'format' => Message::FORMAT_HTML,
            'msg'    => $msg,
        );
    }

    public function createQueue()
    {
        return $this;
    }

    public function main($transport = null)
    {
        $transport OR $transport = new Guzzle(
            $this->getToken(),
            $this->getRoom(),
            $this->getFrom()
        );

        $client = new Client($transport);

        foreach ($this->msgStore as $msg) {
            $message = new Message($this->getNotify(), $this->getBackground());

            switch($msg['format']) {
                case Message::FORMAT_TEXT: $message->setText($msg['msg']);
                                           break;

                case Message::FORMAT_HTML: $message->setHtml($msg['msg']);
                                           break;

                default: throw new BuildException('Unknown message format');
            }

            $client->send($message);
        }
    }
}
