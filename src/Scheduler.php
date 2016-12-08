<?php namespace Coroutine;

use Generator;
use SplQueue;

class Scheduler
{
    protected $tasks;

    public function __construct()
    {
        $this->tasks = new SplQueue();
    }

    public function newTask(Generator $coroutine)
    {
        $task = new Task($coroutine);
        $this->schedule($task);
    }

    public function schedule(Task $task)
    {
        $this->tasks->enqueue($task);
    }

    public function run()
    {
        while (!$this->tasks->isEmpty()) {
            $task = $this->tasks->dequeue();

            $retval = $task->run();
            if ($retval instanceof SystemCall) {
                $retval($task, $this);
                continue;
            }

            if (!$task->isFinished()) {
                $this->schedule($task);
            }
        }
    }
}
