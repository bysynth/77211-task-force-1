<?php

namespace App\Classes\Actions;

class RefuseAction extends BaseAction
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Отказаться';
    }

    /**
     * @inheritDoc
     */
    public function getInnerName(): string
    {
        return 'refuse';
    }

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $executorId;
    }
}
