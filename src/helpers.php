<?php
use Coroutine\ReturnValue;
use Coroutine\Scheduler;
use Coroutine\SystemCall;
use Coroutine\Task;

if (!function_exists('taskId')) {
    function taskId() {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) {
                $task->setMessage($task->id);
                $scheduler->schedule($task);
            }
        );
    }
}

if (!function_exists('newTask')) {
    function newTask(Generator $coroutine) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($coroutine) {
                $task->setMessage($scheduler->newTask($coroutine));
                $scheduler->schedule($task);
            }
        );
    }
}

if (!function_exists('killTask')) {
    function killTask($tid) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($tid) {
                $scheduler->killTask($tid);
                $scheduler->schedule($task);
            }
        );
    }
}

if (!function_exists('retval')) {
    function retval($value) {
        return new ReturnValue($value);
    }
}
