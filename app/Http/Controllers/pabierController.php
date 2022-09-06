<?php

namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Panier;



use Illuminate\Http\Request;

class pabierController extends Controller
{

    private function result($code_result, $result){
        return response(json_encode($result), $code_result)
                  ->header('Content-Type', 'application/json');
    }

    public function get_panier($id=false){
        if (!$id) return $this->result(403 , ['id is required']);
        $panier = Panier::where('commande_id',(int)$id);
        $total = 0;
        $nbArticle = $panier->count();
        foreach ($panier as $key => $value) {
           $article = Article::findOrfail($value->article_id);
           $total += ($this->calculprixttc($article->prix_unitaire)- (1-$article->remise/100) * $article->prix_unitaire) * $value->qte;
        }
        return $this->result(200, [ 'success', 'result'=>['total'=>$total, 'nbr'=>$nbArticle ]]);
    }

    public function add_panier(Request $request){
        if(!isset($request->commande_id)) return $this->result(403 , ['command is required']);
        if(!isset($request->article_id)) return $this->result(403 , ['article is required']);
        if(!isset($request->qte)) return $this->result(403 , ['qte is required']);
        $request->commande_id = (int)$request->commande_id;
        $request->article_id = (int)$request->article_id;
        $request->qte = (double)$request->qte;

        $article = Article::findOrfail($request->article_id);
        if ($article) {
            if($request->qte > $article->qte)return $this->result(403 , ['invalid quantity']);
            $new_item = Panier::create([
                'commande_id' =>  $request->commande_id,
                'article_id' =>  $request->article_id,
                'qte' =>  $request->qte,
    
            ]);

            return $this->result(200, [ 'success', 'result'=>$new_item]);
        }
        return  $this->result(404, [ 'article was not found']);
        



    }

    public function calculprixttc($prixUht){
        $tva = 0.18;
        $prixUttc = $prixUht *(1+$tva);
        return $prixUttc;


    }

    public function calculMontant($id=false){
        if (!$id) return $this->result(403 , ['id is required']);
       
        $commande = Commande::findOrFail($id);
       
        if ($commande) {
            $panier = Panier::where('commande_id',(int)$id);
            $total = 0;
            foreach ($panier as $key => $value) {
                $article = Article::findOrfail($value->article_id);
                $total += ($this->calculprixttc($article->prix_unitaire)- (1-$article->remise/100) * $article->prix_unitaire) * $value->qte;
            
            }
            $total *= (1-$commande->remise);
            $total -= $commande->escompte;
            $total -= $commande->taux_change;
            
            return $this->result(200, [ 'success', 'result'=>$total]);
        }
        return  $this->result(404, [ 'Command was not find']);
        

    }
}
