<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property string fullname
 * @method static whereIn(string $string, $get)
 */
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

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
