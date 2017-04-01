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
    protected $request, $builder;

    protected $filters = [];

    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach($this->getFilters() as $filter => $value) {
            if(method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $builder;

    }

    /**
     * @return array
     */
    protected function getFilters()
    {
        return $this->request->intersect($this->filters);
    }
}