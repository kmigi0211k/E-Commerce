<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Modelo de Producto",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Camiseta Pro"),
 *     @OA\Property(property="description", type="string", example="Camiseta de alta calidad"),
 *     @OA\Property(property="price", type="number", format="float", example=25.99),
 *     @OA\Property(property="stock", type="integer", example=100),
 *     @OA\Property(property="image", type="string", example="products/image.jpg"),
 *     @OA\Property(property="creation_date", type="string", format="date-time")
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'hours_required',
        'image',
        'creation_date',
        'is_active',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }
}
