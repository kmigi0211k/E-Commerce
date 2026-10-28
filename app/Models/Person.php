<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'document',
        'names',
        'last_name',
        'email',
        'phone',
        'address',
        'gender',
        'birthdate',
        'type_document_id',
    ];

    public function typeDocument()
    {
        return $this->belongsTo(TypeDocument::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
