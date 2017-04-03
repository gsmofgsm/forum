<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = ['id'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}" ;
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function getRepliesCountAttribute()
    {
        return $this->replies()->count();
    }
}
