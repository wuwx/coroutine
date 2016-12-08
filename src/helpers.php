<?php
use Coroutine\Scheduler;
use Coroutine\SystemCall;
use Coroutine\Task;

if (!function_exists('newTask')) {
    function newTask(Generator $coroutine) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($coroutine) {
                $scheduler->newTask($coroutine);
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
