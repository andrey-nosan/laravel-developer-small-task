<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Message extends Model
{
    protected $fillable = [
        'subject',
        'body',
        'sent',
    ];

    /**
     * @return MorphToMany
     */
    public function teachers(): MorphToMany
    {
        return $this->morphedByMany(Teacher::class, 'recipient', 'message_recipients');
    }

    /**
     * @return MorphToMany
     */
    public function students(): MorphToMany
    {
        return $this->morphedByMany(Student::class, 'recipient', 'message_recipients');
    }
}
