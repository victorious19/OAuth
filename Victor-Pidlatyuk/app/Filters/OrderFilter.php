<?php

namespace App\Filters;

class OrderFilter extends QueryFilter{
    public function search_field($search_string = ''){
        return $this->builder
            ->where('user_email', 'LIKE', '%'.$search_string.'%')
            ->orWhere('phone', 'LIKE', '%'.$search_string.'%')
            ->orWhere('product', 'LIKE', '%'.$search_string.'%');
    }
}
