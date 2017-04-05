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
    protected $orders = [];
    protected $default_orders = [];

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

        foreach($this->getOrders() as $order => $value) {
            if(method_exists($this, $order)) {
                $this->$order();
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

    /**
     * @return array
     */
    protected function getOrders()
    {
        $orders = $this->request->intersect($this->orders);

        foreach( $this->default_orders as $order ){
            if( ! in_array($order, $orders) ){
                $orders[$order] = 1;
            }
        }

        return $orders;
    }
}