<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = ['id'];
    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('repliesCount', function ($builder){
            $builder->withCount('replies');
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
//            ->withCount('favorites');
//            ->with('owner');
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
}
