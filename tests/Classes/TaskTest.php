<?php

namespace Tests\Classes;

use App\Classes\Actions\CancelAction;
use App\Classes\Actions\DoneAction;
use App\Classes\Actions\RefuseAction;
use App\Classes\Actions\RespondAction;
use App\Classes\Task;
use Exception;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private Task $task;
    private int $customerID;
    private int $executorID;

    protected function setUp(): void
    {
        $this->customerID = 1;
        $this->executorID = 2;
        $this->task = new Task($this->customerID, $this->executorID);
    }

    /**
     * @return array[]
     */
    public function statusesProvider(): array
    {
        return [
            [Task::STATUS_CANCELED, 'cancel'],
            [Task::STATUS_PROCESSING, 'respond'],
            [Task::STATUS_DONE, 'done'],
            [Task::STATUS_FAILED, 'refuse']
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
     * @throws Exception
     */
    public function testGetAvailableActionForCustomer()
    {
        $currentUserId = $this->customerID;
        $this->assertInstanceOf(
            CancelAction::class,
            $this->task->getAvailableAction(Task::STATUS_NEW, $currentUserId)
        );
        $this->assertInstanceOf(
            DoneAction::class,
            $this->task->getAvailableAction(Task::STATUS_PROCESSING, $currentUserId)
        );
    }

    /**
     * @throws Exception
     */
    public function testGetAvailableActionForExecutor()
    {
        $currentUserId = $this->executorID;
        $this->assertInstanceOf(
            RespondAction::class,
            $this->task->getAvailableAction(Task::STATUS_NEW, $currentUserId)
        );
        $this->assertInstanceOf(
            RefuseAction::class,
            $this->task->getAvailableAction(Task::STATUS_PROCESSING, $currentUserId)
        );
    }

    public function testGetAvailableActionForTerminatingStatuses()
    {
        $currentUserId = $this->customerID;
        $this->assertNull($this->task->getAvailableAction(Task::STATUS_FAILED, $currentUserId));
        $this->assertNull($this->task->getAvailableAction(Task::STATUS_DONE, $currentUserId));
        $this->assertNull($this->task->getAvailableAction(Task::STATUS_CANCELED, $currentUserId));
    }
}
