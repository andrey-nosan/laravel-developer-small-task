<?php

namespace App\Models;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @method static paginate(Repository|Application $config)
 */
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
