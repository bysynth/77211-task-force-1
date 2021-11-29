<?php

namespace App\Classes\Actions;

abstract class BaseAction
{
    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return string
     */
    abstract public function getInnerName(): string;

    /**
     * @param int $executorId
     * @param int $customerId
     * @param int $currentUserId
     * @return bool
     */
    abstract public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool;
}
