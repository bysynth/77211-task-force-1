<?php

namespace App\Actions;

abstract class AbstractAction
{
    protected string $name;
    protected string $code;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param int $executorId
     * @param int $customerId
     * @param int $currentUserId
     * @return bool
     */
    abstract public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool;
}
