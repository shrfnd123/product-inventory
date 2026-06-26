<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     public int $id;

    public string $name;

    public float $price;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
    ];
}