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

    public function schedule(Task $task)
    {
        $this->tasks->enqueue($task);
    }

    public function run()
    {
        while (!$this->tasks->isEmpty()) {
            $task = $this->tasks->dequeue();
            $task->run();
            if (!$task->isFinished()) {
                $this->schedule($task);
            }
        }
    }
}
