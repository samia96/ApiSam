<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'reference',
        'description',
        'prix_unitaire',
        'qte',
        'remise',
    ];

    public function commande()
    {
        return $this->hasOne(Commande::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }
   
}
