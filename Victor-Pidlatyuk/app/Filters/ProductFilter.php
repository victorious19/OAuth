<?php

namespace App\Filters;

class ProductFilter extends QueryFilter{
    public function category_id($id = null){
        return $this->builder->when($id, function($query, $id){
            $query->whereIn('category_id', $this->paramToArray($id));
        });
    }
    public function search_field($search_string = ''){
        return $this->builder
            ->where('title', 'LIKE', '%'.$search_string.'%')
            ->orWhere('description', 'LIKE', '%'.$search_string.'%');
    }
    public function min_price($price = 0){
        return $this->builder->where('price', '>=', $price);
    }
    public function max_price($price = 0){
        return $this->builder->where('price', '<=', $price);
    }
    public function date($order = 'asc'){
        return $this->builder->orderBy('created_at', $order);
    }
    public function name($order = 'asc'){
        return $this->builder->orderBy('title', $order);
    }
    public function price($order = 'asc'){
        return $this->builder->orderBy('price', $order);
    }
}
