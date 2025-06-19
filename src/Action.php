<?php

declare(strict_types=1);

namespace Activity;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'performer_id',
        'performer_type',
        'subject_type',
        'subject_id',
        'action_type',
    ];

    public function performer()
    {
        return $this->morphTo();
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function getDescription(): string
    {   
        // We can add more details like:
        // - performer name
        // - subject name
        // - timestamp
        // - etc.
        return __('The model was ' . $this->action_type);
    }
}