<?php
/**
 * Created by PhpStorm.
 * User: gsm
 * Date: 2017/4/14
 * Time: 19:04
 */

namespace App;


trait favoriable
{

    public function favorite()
    {
        if (!$this->isFavorited()) {
            $this->favorites()->create(['user_id' => auth()->id()]);
        }
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }
}