<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->with('parentCategory');
    }

    public static function productsFilters(): array
    {
        $productsFilters['fabricArray'] = ['Cotton', 'Polyester', 'Wool',];
        $productsFilters['sleeveArray'] = ['Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless',];
        $productsFilters['patternArray'] = ['Checked', 'Plain', 'Printed', 'Self', 'Solid',];
        $productsFilters['fitArray'] = ['Regular', 'Slim',];
        $productsFilters['occasionArray'] = ['Causal', 'Formal',];

        return $productsFilters;
    }
}
