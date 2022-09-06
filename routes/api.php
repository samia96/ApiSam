<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\client_controller;
use App\Http\Controllers\articleController;
use App\Http\Controllers\pabierController;
use App\Http\Controllers\commandeController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get_clients/', [client_controller::class, 'index']);
Route::get('get_client/{id,commande_id}', [client_controller::class, 'get_client']);
Route::post('create_client/', [client_controller::class, 'store']);


Route::get('get_articles/', [articleController::class, 'index']);
Route::get('get_article/{id}', [articleController::class, 'get_article']);
Route::post('create_article/', [articleController::class, 'store']);
Route::post('create_articles/', [articleController::class, 'storeMultiple']);

Route::get('get_panier/{id}', [pabierController::class, 'get_panier']);
Route::post('add_panier/', [pabierController::class, 'add_panier']);

Route::get('get_commandes/', [commandeController::class, 'index']);
Route::post('valider_commande/', [commandeController::class, 'valider']);
Route::get('new_devis/', [commandeController::class, 'new_devis']);



