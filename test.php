<?php

require_once 'vendor/autoload.php';

use App\Classes\Task;

$test = new Task(1, 2);

assert($test->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCELED);
assert($test->getNextStatus(Task::ACTION_RESPOND) === Task::STATUS_PROCESSING);
assert($test->getNextStatus(Task::ACTION_DONE) === Task::STATUS_DONE);
assert($test->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED);

assert($test->getAvailableAction(Task::STATUS_NEW) === [Task::ACTION_CANCEL, Task::ACTION_RESPOND]);
assert($test->getAvailableAction(Task::STATUS_PROCESSING) === [Task::ACTION_DONE, Task::ACTION_REFUSE]);
assert($test->getAvailableAction(Task::STATUS_CANCELED) === []);
assert($test->getAvailableAction(Task::STATUS_DONE) === []);
assert($test->getAvailableAction(Task::STATUS_FAILED) === []);
