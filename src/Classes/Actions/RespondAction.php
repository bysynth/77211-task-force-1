<?php

namespace App\Classes\Actions;

class RespondAction extends BaseAction
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Откликнуться';
    }

    /**
     * @inheritDoc
     */
    public function getInnerName(): string
    {
        return 'respond';
    }

    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId !== $customerId;
    }
}
