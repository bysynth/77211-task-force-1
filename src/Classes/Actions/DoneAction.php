<?php

namespace App\Classes\Actions;

class DoneAction extends BaseAction
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Выполнено';
    }

    /**
     * @inheritDoc
     */
    public function getInnerName(): string
    {
        return 'done';
    }

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}
