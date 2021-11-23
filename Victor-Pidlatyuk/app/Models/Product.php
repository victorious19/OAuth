<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    function pictures() {
        return $this->hasMany(ProductPicture::class);
    }
    function complete_orders() {
        return Order::where('product', $this->title)->where('status', 'complete')->get();
    }
    function user() {
        return $this->belongsTo(User::class);
    }
    function category() {
        return $this->belongsTo(Category::class)->first();
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
        'title',
        'description',
        'category_id',
        'price'
    ];
}
