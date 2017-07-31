<?php
require_once dirname(__FILE__) . '/Task.php';

$task = new Umbrella\components\cron\task\Task();
$listTask = $task->taskFactory();