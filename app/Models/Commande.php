<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_commande',
        'clent_id',
        'centre_profit',
        'code_affaire',
        'reference externe',
        'date_creation',
        'date_validation',
        'date_livraison',
        'addresse_livraison',
        'addresse_facturation',
        'remise',
        'escompte',
        'taux_change',
        'montant',
        'reste_du'
    
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id' );
    }

}
