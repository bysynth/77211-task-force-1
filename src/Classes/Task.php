<?php

namespace App\Classes;

use App\Classes\Actions\BaseAction;
use App\Classes\Actions\CancelAction;
use App\Classes\Actions\DoneAction;
use App\Classes\Actions\RefuseAction;
use App\Classes\Actions\RespondAction;
use Exception;

class Task
{
    public const STATUS_NEW = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_PROCESSING = 3;
    public const STATUS_DONE = 4;
    public const STATUS_FAILED = 5;

    public const ACTION_STATUS_MAP = [
        'cancel' => self::STATUS_CANCELED,
        'respond' => self::STATUS_PROCESSING,
        'done' => self::STATUS_DONE,
        'refuse' => self::STATUS_FAILED
    ];

    private int $customerId;
    private ?int $executorId;
    private int $status = self::STATUS_NEW;

    /**
     * @param int $customerId
     * @param int|null $executorId
     */
    public function __construct(int $customerId, int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    /**
     * @return array
     */
    public function getStatusesList(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_PROCESSING => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /**
     * @param string $action
     * @return string
     * @throws Exception
     */
    public function getNextStatus(string $action): string
    {
        return array_key_exists(
            $action,
            self::ACTION_STATUS_MAP
        ) ? self::ACTION_STATUS_MAP[$action] : throw new Exception("Unknown action $action");
    }

    /**
     * @param int $status
     * @param int $currentUserId
     * @return BaseAction|null
     * @throws Exception
     */
    public function getAvailableAction(int $status, int $currentUserId): ?BaseAction
    {
        $action = null;

        switch ($status) {
            case self::STATUS_NEW:
                if (CancelAction::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $action = new CancelAction();
                }

                if (RespondAction::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $action = new RespondAction();
                }
                break;
            case self::STATUS_PROCESSING:
                if (DoneAction::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $action = new DoneAction();
                }

                if (RefuseAction::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $action = new RefuseAction();
                }
                break;
            case self::STATUS_FAILED:
            case self::STATUS_DONE:
            case self::STATUS_CANCELED:
                return null;
            default:
                throw new Exception("Unknown status $status");
        }

        return $action;
    }
}
