<?php namespace Coroutine;

use Generator;

class Task
{
    protected $coroutine;
    protected $started;

    public function __construct(Generator $coroutine)
    {
        $this->started = false;
        $this->coroutine = $coroutine;
    }

    public function run()
    {
        if ($this->started) {
            return $this->coroutine->send(null);
        } else {
            $this->started = true;
            return $this->coroutine->current();
        }

    }

    public function isFinished() {
        return !$this->coroutine->valid();
    }
}
