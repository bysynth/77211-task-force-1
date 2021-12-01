<?php

namespace App\Actions;

class ActionDone extends AbstractAction
{
    protected string $name = 'Выполнено';
    protected string $code = 'done';

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}
