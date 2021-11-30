<?php

namespace App\Classes\Actions;

class ActionRespond extends AbstractAction
{
    protected string $name = 'Откликнуться';
    protected string $code = 'respond';

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId !== $customerId;
    }
}
