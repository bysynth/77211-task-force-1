<?php

namespace App\Classes\Actions;

class CancelAction extends BaseAction
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Отменить';
    }

    /**
     * @inheritDoc
     */
    public function getInnerName(): string
    {
        return 'cancel';
    }

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}
