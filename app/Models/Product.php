<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method firstOrCreate(string[] $array)
 * @method isValid()
 * @method create(array $array)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','price','slug','description'
    ];

    protected $with = ['categories'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
