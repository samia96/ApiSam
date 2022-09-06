<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();

            $table->string('code_commande')->nullable();
            $table->string('client_id')->nullable();
            $table->integer('valider')->nullable();
            $table->string('centre_profit')->nullable();
            $table->string('code_affaire')->nullable();
            $table->string('reference_externe')->nullable();
            $table->string('date_creation')->nullable();
            $table->string('date_validation')->nullable();
            $table->string('date_livraison')->nullable();
            $table->string('addresse_livraison')->nullable();
            $table->string('addresse_facturation')->nullable();
            $table->string('remise')->nullable();
            $table->string('escompte')->nullable();
            $table->string('taux_change')->nullable();
            $table->string('montant')->nullable();
            $table->string('reste_du')->nullable();
            $table->string('confirmDevi')->default('No');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande');
    }
}
