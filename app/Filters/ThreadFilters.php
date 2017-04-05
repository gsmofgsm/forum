<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];
    protected $orders = ['popular'];
    protected $default_orders = ['latest'];

    /**
     * Filter a thread by a given username
     * @param string $username
     * @return mixed
     * @internal param string $builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    protected function popular()
    {
        return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function latest()
    {
        return $this->builder->orderBy('created_at', 'desc');
    }
}