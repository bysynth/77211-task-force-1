<?php

namespace Tests\Classes;

use App\Classes\Task;
use Exception;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private Task $task;

    protected function setUp(): void
    {
        $this->task = new Task(1, 2);
    }

    /**
     * @return array[]
     */
    public function statusesProvider(): array
    {
        return [
            [Task::STATUS_CANCELED, Task::ACTION_CANCEL],
            [Task::STATUS_PROCESSING, Task::ACTION_RESPOND],
            [Task::STATUS_DONE, Task::ACTION_DONE],
            [Task::STATUS_FAILED, Task::ACTION_REFUSE]
        ];
    }

    /**
     * @return array[]
     */
    public function actionsProvider(): array
    {
        return [
            [Task::STATUS_NEW, [Task::ACTION_CANCEL, Task::ACTION_RESPOND]],
            [Task::STATUS_PROCESSING, [Task::ACTION_DONE, Task::ACTION_REFUSE]],
            [Task::STATUS_CANCELED, []],
            [Task::STATUS_DONE, []],
            [Task::STATUS_FAILED, []]
        ];
    }

    /**
     * @param string $status
     * @param string $action
     * @throws Exception
     * @dataProvider statusesProvider
     */
    public function testGetNextStatus(string $status, string $action)
    {
        $this->assertEquals($status,$this->task->getNextStatus($action));
    }

    /**
     * @param string $status
     * @param array $actions
     * @throws Exception
     * @dataProvider actionsProvider
     */
    public function testGetAvailableAction(string $status, array $actions)
    {
        $this->assertEquals($actions, $this->task->getAvailableAction($status));
    }
}
