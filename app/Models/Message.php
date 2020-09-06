<?php

namespace App\Models;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

/**
 * @method static paginate(Repository|Application $config)
 * @method static create(array $attributes)
 * @method static find(Message|int $message)
 * @property string body
 * @property string body_url
 * @property string body_content
 * @property mixed teachers
 * @property mixed students
 * @property string subject
 * @property boolean sent
 */
class Message extends Model
{
    protected $fillable = [
        'subject',
        'body',
        'sent',
    ];

    protected $appends = [
        'body_content',
        'is_sent',
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

    /**
     * @return string
     */
    public function getBodyContentAttribute(): string
    {
        return Storage::exists($this->body) ? Storage::get($this->body) : '';
    }

    /**
     * @return string
     */
    public function getBodyUrlAttribute(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getIsSentAttribute(): string
    {
        return $this->sent ? __('Yes') : __('No');
    }
}
