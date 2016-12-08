<?php namespace Coroutine;

use Generator;

class Task
{
    public $id;
    protected $coroutine;
    protected $started;
    protected $message;

    public function __construct(Generator $coroutine)
    {
        $this->started = false;
        $this->coroutine = $coroutine;
    }

    public function run()
    {
        if ($this->started) {
            $retval = $this->coroutine->send($this->message);
            $this->message = null;
            return $retval;
        } else {
            $this->started = true;
            return $this->coroutine->current();
        }

    }

    public function setMessage($value)
    {
        $this->message = $value;
    }

    public function isFinished() {
        return !$this->coroutine->valid();
    }
}
