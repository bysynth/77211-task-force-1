<?php

namespace App\Classes\Actions;

class ActionRefuse extends AbstractAction
{
    protected string $name = 'Отказаться';
    protected string $code = 'refuse';

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $executorId;
    }
}
