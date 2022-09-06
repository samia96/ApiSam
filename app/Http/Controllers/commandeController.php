<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Commande;

class commandeController extends Controller
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
        return $this->result(200, Commande::all());
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function valider(Request $request)
    {
        
        if(!isset($request->code_commande)) return $this->result(403 , ['code command is required']);
        if(!isset($request->commande_id)) return $this->result(403 , ['commande_id is required']);
        if(!isset($request->centre_profit)) return $this->result(403 , ['centre profit is required']);
        if(!isset($request->code_affaire)) return $this->result(403 , ['code affaire is required']);
        if(!isset($request->reference_externe)) return $this->result(403 , ['reference is required']);
        if(!isset($request->date_creation)) return $this->result(403 , ['date creation is required']);
        if(!isset($request->date_validation)) return $this->result(403 , ['date validation is required']);
        if(!isset($request->date_livraison)) return $this->result(403 , ['date livraison is required']);
        if(!isset($request->addresse_livraison)) return $this->result(403 , ['address livraison is required']);
        if(!isset($request->addresse_facturation)) return $this->result(403 , ['address facturation is required']);
        if(!isset($request->remise)) return $this->result(403 , ['remise is required']);
        if(!isset($request->escompte)) return $this->result(403 , ['escompte is required']);
        if(!isset($request->taux_change)) return $this->result(403 , ['taux_change is required']);
        if(!isset($request->montant)) return $this->result(403 , ['montant is required']);
        $request->code_commande = htmlentities($request->code_commande);
        $request->commande_id = (int)($request->commande_id);
        $request->client_id = htmlentities($request->client_id);
        $request->centre_profit = htmlentities($request->centre_profit);
        $request->code_affaire = htmlentities($request->code_affaire);
        $request->reference_externe = htmlentities($request->reference_externe);
        $request->date_creation = htmlentities($request->date_creation);
        $request->date_validation = htmlentities($request->date_validation);
        $request->date_livraison = htmlentities($request->date_livraison);
        $request->addresse_livraison = htmlentities($request->addresse_livraison);
        $request->addresse_facturation = htmlentities($request->addresse_facturation);
        $request->escompte = (double)$request->escompte;
        $request->montant = (double)$request->montant;
        $request->taux_change = (double)$request->taux_change;
        $request->remise = (double)$request->remise;




        $commande = Commande::findOrFail($commande_id);
        if($commande){

            $commande->update([
                'code_commande' =>  $request->code_commande,
                'client_id'     => $request->client_id,
                'centre_profit' =>  $request->centre_profit,
                'code_affaire' =>  $request->code_affaire,
                'reference_externe' =>  $request->reference_externe,
                'date_creation' =>  $request->date_creation,
                'date_validation' =>  $request->date_validation,
                'date_livraison' =>  $request->date_livraison,
                'addresse_livraison' =>  $request->addresse_livraison,
                'taux_change' =>  $request->taux_change,
                'escompte' =>  $request->escompte,
                'remise' =>  $request->remise,
                'montant' =>  $request->montant,
                'confirmDevi' => 'Yes',
                'restant_du' =>  $request->montant- $request->escompte,      
                



    
            ]);
            return $this->result(200, [ 'success', 'result'=>$commande]);

        }  return $this->result(404, ['failed','commande not found']);
      
       
    }

    public function new_devis(){
        $new_commande = Commande::create([]);
        return $this->result(200, [ 'success', 'result'=>$new_commande]);
    }

   public function cancelCommande($commande_id=false){
    if (!$commande_id) return $this->result(403 , ['commande_id is required']);
    $command = Commande::findOrFail($commande_id);

    if($command){
        $panier = Panier::where('commande_id', $commande_id )->get();
        foreach ($panier as $key => $value) {
            Panier::destroy($value->id);
        }
        $command->delete();
        return $this->result(200, [ 'success', 'result'=>'0']);
    }
    return  $this->result(404, [ 'commande not found']);

   }

   
}
