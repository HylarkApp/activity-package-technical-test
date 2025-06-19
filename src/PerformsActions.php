<?php

declare(strict_types=1);

namespace Activity;

trait PerformsActions
{
    public function performedActions()
    {
        return $this->morphMany(Action::class, 'performer');
    }
}