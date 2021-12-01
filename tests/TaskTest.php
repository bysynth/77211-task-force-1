<?php

namespace Tests;

use App\Actions\ActionConfirm;
use App\Actions\ActionCancel;
use App\Actions\ActionDone;
use App\Actions\ActionRefuse;
use App\Actions\ActionRespond;
use App\Task;
use Exception;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private const CUSTOMER_ID = 1;
    private const EXECUTOR_ID = 2;
    private Task $task;

    protected function setUp(): void
    {
        $this->task = new Task(self::CUSTOMER_ID, self::EXECUTOR_ID);
    }

    /**
     * @return array[]
     */
    public function statusesProvider(): array
    {
        return [
            [Task::STATUS_NEW, 'respond'],
            [Task::STATUS_CANCELED, 'cancel'],
            [Task::STATUS_PROCESSING, 'confirm'],
            [Task::STATUS_DONE, 'done'],
            [Task::STATUS_FAILED, 'refuse']
        ];
    }

    /**
     * @return array[]
     */
    public function actionsProvider(): array
    {
        return [
            [[new ActionConfirm(), new ActionCancel()], Task::STATUS_NEW, self::CUSTOMER_ID],
            [[new ActionRespond()], Task::STATUS_NEW, self::EXECUTOR_ID],
            [[new ActionDone()], Task::STATUS_PROCESSING, self::CUSTOMER_ID],
            [[new ActionRefuse()], Task::STATUS_PROCESSING, self::EXECUTOR_ID],
            [[], Task::STATUS_FAILED, self::CUSTOMER_ID],
            [[], Task::STATUS_DONE, self::CUSTOMER_ID],
            [[], Task::STATUS_CANCELED, self::CUSTOMER_ID],
        ];
    }

    /**
     * @param int $status
     * @param string $action
     * @throws Exception
     * @dataProvider statusesProvider
     */
    public function testGetNextStatus(int $status, string $action)
    {
        $this->assertEquals($status, $this->task->getNextStatus($action));
    }


    /**
     * @param array $actions
     * @param int $status
     * @param int $currentUserId
     * @throws Exception
     * @dataProvider actionsProvider
     */
    public function testGetAvailableActions(array $actions, int $status, int $currentUserId)
    {
        $this->assertEquals($actions, $this->task->getAvailableActions($status, $currentUserId));
    }
}
