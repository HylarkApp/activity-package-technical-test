<?php

declare(strict_types=1);

namespace Activity;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActions
{
    // Thanks to: https://laravel.com/docs/5.0/eloquent#global-scopes
    public static function bootHasActions()
    {
        static::created(function ($model) {
            $model->logAction('created');
        });

        static::updated(function ($model) {
            $model->logAction('updated');
        });

        static::deleted(function ($model) {
            $model->logAction('deleted');
        });
    }

    public function actions(): MorphMany
    {
        return $this->morphMany(Action::class, 'subject');
    }

    protected function logAction(string $type): void
    {
        if (! $this->id || !is_int($this->id)) {
            $msg = "Action log skipped: subject model has no valid integer ID (" . get_class($this) . ")";
            logger()->warning($msg);
            return;
        }

        //TODO: Improvements (*)
        // $payload = null;

        // if ($type === 'updated') {
        //     $payload = [
        //         'old' => $this->getOriginal(),
        //         'new' => $this->getDirty(),
        //     ];
        // }
        
        // $performerName = $user ? $user->name ?? '[Unknown]' : '[System]';

        try {
            $user = auth()->user();

            Action::create([
                'performer_id' => $user->id ?? null,
                'performer_type' => $user ? get_class($user) : null,
                // * 'performer_name' => $performerName,
                'subject_type' => get_class($this),
                'subject_id' => $this->id,
                'action_type' => $type,
                // * 'versions' => $payload ? json_encode($payload) : null,
            ]);            
        } catch (\Exception $e) {
            logger()->error("Failed to log action: " . $e->getMessage());
        }
    }
}