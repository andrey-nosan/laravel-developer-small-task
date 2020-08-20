<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Teacher extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * @return MorphToMany
     */
    public function messages(): MorphToMany
    {
        return $this->morphToMany(Message::class, 'recipient', 'message_recipients');
    }
}
