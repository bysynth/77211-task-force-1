<?php

namespace App\Actions;

class ActionConfirm extends AbstractAction
{
    protected string $name = 'Подтвердить';
    protected string $code = 'confirm';

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}
