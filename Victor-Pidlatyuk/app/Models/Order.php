<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Order extends Model
{
    use HasFactory;
    public $timestamps = [ "created_at" ];
    const UPDATED_AT = null;
    function product() {
        return $this->belongsTo(Product::class);
    }
    public function scopeFilter(Builder $builder, QueryFilter $filter){
        return $filter->apply($builder);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product',
        'user_full_name',
        'user_email',
        'phone',
        'price',
        'status',
    ];
}
