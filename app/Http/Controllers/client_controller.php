<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class client_controller extends Controller
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
         return $this->result(200, Client::all());
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
        if(!isset($request->prenom)) return $this->result(403 , ['lastname is required']);
        if(!isset($request->tel)) return $this->result(403 , ['phone is required']);
        $request->nom = htmlentities($request->nom);
        $request->prenom = htmlentities($request->prenom);
        $request->tel = (double)$request->tel;

        $new_client = Client::create([
            'nom' =>  $request->nom,
            'prenom' =>  $request->prenom,
            'tel' =>  $request->tel,

        ]);

      
        return $this->result(200, [ 'success', 'result'=>$new_client]);
        
    }

    public function get_client($id=false, $commande_id){
        if (!$id) return $this->result(403 , ['id is required']);
        if (!$commande_id) return $this->result(403 , ['commande_id is required']);
        $command = Commande::findOrFail($commande_id);
        if($command){
            $client = Client::findOrFail($commande_id);
            if(!$client) return $this->result(403 , ['client not found']);
            $command->update([
                'client_id'=> $client->id
            ]);
            return $this->result(200, [ 'success', 'result'=>$client]);
        }
        return  $this->result(404, [ 'commande not found']);
       

        
    }

}