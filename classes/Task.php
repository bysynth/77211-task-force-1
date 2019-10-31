<?php

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_CANCEL = 'cancel';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_DONE = 'done';
    public const ACTION_REFUSE = 'refuse';

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_IMPLEMENTER = 'implementer';

    public const ACTION_STATUS_MAP = [
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_RESPOND => self::STATUS_PROCESSING,
        self::ACTION_DONE => self::STATUS_DONE,
        self::ACTION_REFUSE => self::STATUS_FAILED
    ];

    private $customerId;
    private $implementerId;
    private $completionDate;
    private $activeStatus = self::STATUS_NEW;

    /**
     * Task constructor.
     * @param int $customerId
     * @param int $implementerId
     * @param string $completionDate
     */
    public function __construct(int $customerId, int $implementerId, string $completionDate)
    {
        $this->customerId = $customerId;
        $this->implementerId = $implementerId;
        $this->completionDate = $completionDate;
    }

    /**
     * @return array
     */
    public function getStatusesList(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CANCELED,
            self::STATUS_PROCESSING,
            self::STATUS_DONE,
            self::STATUS_FAILED
        ];
    }

    /**
     * @return array
     */
    public function getActionsList(): array
    {
        return [
            self::ACTION_CANCEL,
            self::ACTION_RESPOND,
            self::ACTION_DONE,
            self::ACTION_REFUSE
        ];
    }

    /**
     * @param string $action
     * @return string|null
     */
    public function getNextStatus(string $action): ?string
    {
        return in_array($action, $this->getActionsList(), true) ? self::ACTION_STATUS_MAP[$action] : null;
    }
}
