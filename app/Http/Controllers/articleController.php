<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;

class articleController extends Controller
{
    private function result($code_result, $result){
        return response(json_encode($result), $code_result)
                  ->header('Content-Type', 'application/json');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        return $this->result(200, Article::all());
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!isset($request->nom)) return $this->result(403 , ['name is required']);
        if(!isset($request->reference)) return $this->result(403 , ['reference is required']);
        if(!isset($request->description)) return $this->result(403 , ['description is required']);
        if(!isset($request->prix_unitaire)) return $this->result(403 , ['price is required']);
        if(!isset($request->qte)) return $this->result(403 , ['quantity is required']);
        if(!isset($request->remise)) return $this->result(403 , ['remise is required']);
        $request->nom = htmlentities($request->nom);
        $request->reference = htmlentities($request->reference);
        $request->description = htmlentities($request->description);
        $request->prix_unitaire = (double)$request->prix_unitaire;
        $request->qte = (double)$request->qte;
        $request->remise = (double)$request->remise;

        $result = $this->store_item($request);
        $result->prixTHt = $request->prix_unitaire * $request->qte;
        $result->prixUttc = $this->calculprixttc($request->prix_unitaire);
        $result->prixTttc = $this->calculpTttc($result[$key]->prixUttc, $request->qte,  $request->remise);

        return $this->result(200, [ 'success', $result]);
        
    }

    public function store_item($item){

        $new_article = Article::create([
            'nom' =>  $item->nom,
            'reference' =>  $item->reference,
            'description' =>  $item->description,
            'prix_unitaire' =>  $item->prix_unitaire,
            'qte' =>  $item->qte,
            'remise' =>  $item->remise,

        ]);

        return Article::findOrFail($new_article->id);
        
    }

    public function storeMultiple(Request $request){
        $result = [];
        //$request = json_decode($request);
        foreach ($request->body as $key => $item) {
             $item= (object) $item;
            if(!isset($item->nom)) return $this->result(403 , ['name '.$key.' is required']);
            if(!isset($item->reference)) return $this->result(403 , ['reference is required']);
            if(!isset($item->description)) return $this->result(403 , ['description is required']);
            if(!isset($item->prix_unitaire)) return $this->result(403 , ['price is required']);
            if(!isset($item->qte)) return $this->result(403 , ['quantity is required']);
            if(!isset($item->remise)) return $this->result(403 , ['remise is required']);
            $item->nom = htmlentities($item->nom);
            $item->reference = htmlentities($item->reference);
            $item->description = htmlentities($item->description);
            $item->prix_unitaire = (double)$item->prix_unitaire;
            $item->qte = (double)$item->qte;
            $item->remise = (double)$item->remise;

            $result[$key] = $this->store_item($item);
            $result[$key]->prixTHt = $item->prix_unitaire * $item->qte;
            $result[$key]->prixUttc = $this->calculprixttc($item->prix_unitaire);
            $result[$key]->prixTttc = $this->calculpTttc($result[$key]->prixUttc, $item->qte,  $item->remise);

        }

        return $this->result(200, ['success',$result]);
    }

    public function get_article($id=false){
        if (!$id) return $this->result(403 , ['id is required']);
        $article = Article::findOrfail((int)$id);
        $article->prixUttc = $this->calculprixttc($article->prix_unitaire)- (1-$article->remise/100) * $article->prix_unitaire;
      

        return $this->result(200, [ 'success', $article]);
        
    }
  
  

    public function calculprixttc($prixUht){
        $tva = 0.18;
        $prixUttc = $prixUht *(1+$tva);
        return $prixUttc;


    }

    public function calculpTttc($prixUttc,$qte,$remise){
        return $prixUttc * $qte - $remise;
    }


    
}
