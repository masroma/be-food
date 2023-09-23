<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image', 'image_1','image_2','image_3','image_4', 'title', 'slug', 'category_id', 'user_id', 'description', 'weight', 'price', 'stock', 'discount'
    ];

    /**
     * category
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * reviews
     *
     * @return void
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * getImageAttribute
     *
     * @param  mixed $image
     * @return void
     */
    // public function getImageAttribute($image)
    // {
    //     return asset('storage/products/' . $image);
    // }

     /**
     * getImage1Attribute
     *
     * @param  mixed $image1
     * @return voids
     */
    // public function getImage1Attribute($image1)
    // {
    //     return asset('storage/products/' . $image1);
    // }
        
    /**
     * getReviewsAvgRatingAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getReviewsAvgRatingAttribute($value) {
        return $value ? substr($value, 0, 3) : 0;
    }
}
