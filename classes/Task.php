<?php

class Task
{
    public const STATUS_NEW = 'Новое';
    public const STATUS_CANCELED = 'Отменено';
    public const STATUS_PROCESSING = 'В работе';
    public const STATUS_DONE = 'Выполнено';
    public const STATUS_FAILED = 'Провалено';

    public const ACTION_CANCEL = 'Отменить';
    public const ACTION_RESPOND = 'Откликнуться';
    public const ACTION_DONE = 'Выполнено';
    public const ACTION_REFUSE = 'Отказаться';

    public const ROLE_CUSTOMER = 'Заказчик';
    public const ROLE_IMPLEMENTER = 'Исполнитель';

    public const ACTION_STATUS_MAP = [
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_RESPOND => self::STATUS_PROCESSING,
        self::ACTION_DONE => self::STATUS_DONE,
        self::ACTION_REFUSE => self::STATUS_FAILED
    ];

    private $customerId;
    private $implementerId;
    private $completionDate;
    private $activeStatus;

    public function __construct($customerId, $implementerId, $completionDate)
    {
        $this->customerId = $customerId;
        $this->implementerId = $implementerId;
        $this->completionDate = $completionDate;
        $this->activeStatus = self::STATUS_NEW;
    }

    public function getStatusesList()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CANCELED,
            self::STATUS_PROCESSING,
            self::STATUS_DONE,
            self::STATUS_FAILED
        ];
    }

    public function getActionsList()
    {
        return [
            self::ACTION_CANCEL,
            self::ACTION_RESPOND,
            self::ACTION_DONE,
            self::ACTION_REFUSE
        ];
    }

    public function getNextStatus($action)
    {
        if (in_array($action, $this->getActionsList(), true)) {
            return self::ACTION_STATUS_MAP[$action];
        }

        return null;
    }
}
