<?php

namespace App;

use App\Actions\ActionCancel;
use App\Actions\ActionConfirm;
use App\Actions\ActionDone;
use App\Actions\ActionRefuse;
use App\Actions\ActionRespond;
use Exception;

class Task
{
    public const STATUS_NEW = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_PROCESSING = 3;
    public const STATUS_DONE = 4;
    public const STATUS_FAILED = 5;

    public const ACTION_STATUS_MAP = [
        'confirm' => self::STATUS_PROCESSING,
        'cancel' => self::STATUS_CANCELED,
        'respond' => self::STATUS_NEW,
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
     * @return array
     * @throws Exception
     */
    public function getAvailableActions(int $status, int $currentUserId): array
    {
        $actions = [];

        switch ($status) {
            case self::STATUS_NEW:
                if (ActionConfirm::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionConfirm();
                }

                if (ActionCancel::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionCancel();
                }

                if (ActionRespond::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionRespond();
                }
                break;
            case self::STATUS_PROCESSING:
                if (ActionDone::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionDone();
                }

                if (ActionRefuse::isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionRefuse();
                }
                break;
            case self::STATUS_FAILED:
            case self::STATUS_DONE:
            case self::STATUS_CANCELED:
                return [];
            default:
                throw new Exception("Unknown status $status");
        }

        return $actions;
    }
}
