<?php
/**
 * Created by PhpStorm.
 * User: gsm
 * Date: 2017/4/1
 * Time: 10:47
 */

namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{

    /**
     * @var Request
     */
    protected $request, $builder;

    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        if ($this->request->has('by')) {
            return $this->by($this->request->by);
        }

        return $builder;

    }
}