<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    public function products() {
        return $this->hasMany(Product::class);
    }

    public static function getBySymbol(string $symbol): Model {{
        return self::query()->where('symbol', $symbol)->first();
    }}
}
