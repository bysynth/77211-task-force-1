<?php

require_once 'classes\Task.php';

$test = new Task(1, 2, '2019-11-10');

assert($test->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCELED, 'Должен быть статус "Отменено"');
assert($test->getNextStatus(Task::ACTION_RESPOND) === Task::STATUS_PROCESSING, 'Должен быть статус "В работе"');
assert($test->getNextStatus(Task::ACTION_DONE) === Task::STATUS_DONE, 'Должен быть статус "Выполнено"');
assert($test->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED, 'Должен быть статус "Провалено"');
