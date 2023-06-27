<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * @method firstOrCreate(string[] $array)
     */
    class Category extends Model
    {
        use HasFactory;

        protected $fillable = ['product_id','name'];

        protected $visible = ['id','product_id','name'];

        public function product(): BelongsTo
        {
            return $this->belongsTo(Product::class)->withDefault();
        }

    }
