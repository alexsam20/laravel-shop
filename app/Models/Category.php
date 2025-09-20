<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function parentCategory(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'parent_id')
            ->select('id', 'category_name', 'url')
            ->where('status', 1);
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')
            ->where('status', 1);
    }

    public static function getCategories(): array
    {
        return  Category::with(['subCategories' => function ($query) {
                $query->with('subCategories');
            }])
            ->where('parent_id', 0)
            ->where('status', 1)
            ->get()
            ->toArray();

//        return $getCategories;
    }
}
