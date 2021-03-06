<?php namespace Coroutine;

use Generator;
use SplQueue;

class Scheduler
{
    protected $queue;
    protected $tasks;

    public function __construct()
    {
        $this->tasks = [];
        $this->queue = new SplQueue();
    }

    public function newTask(Generator $coroutine)
    {
        $task = new Task($coroutine);
        $task->id = max(array_merge(array_keys($this->tasks), [0])) + 1;
        $this->tasks[$task->id] = $task;
        $this->schedule($task);
        return $task->id;
    }

    public function killTask($task_id)
    {
        if (!isset($this->tasks[$task_id])) {
            return false;
        }

        unset($this->tasks[$task_id]);

        foreach ($this->queue as $i => $task) {
            if ($task->id === $task_id) {
                unset($this->queue[$i]);
                break;
            }
        }

        return true;
    }

    public function schedule(Task $task)
    {
        $this->queue->enqueue($task);
    }

    public function run()
    {
        while (!$this->queue->isEmpty()) {
            $task = $this->queue->dequeue();

            $retval = $task->run();
            if ($retval instanceof SystemCall) {
                $retval($task, $this);
                continue;
            }

            if ($task->isFinished()) {
                unset($this->tasks[$task->id]);
            } else {
                $this->schedule($task);
            }
        }
    }
}
