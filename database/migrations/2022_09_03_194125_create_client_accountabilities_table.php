<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAccountabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_accountabilities', function (Blueprint $table) {
            $table->id();

            //?FK Client One to One
            $table->foreignId("client_id")->nullable()->constrained('clients')->nullOnDelete();
            $table->string('revenue'); // Somma del campo amount dei pagamenti effettuati dal cliente (movements)
            $table->string('total_due'); //Conto totale da pagare accumulato
            $table->string('unpaid'); //non pagato - Differenza totale e conto già pagato. total_due - revenue

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
        Schema::dropIfExists('client_accountabilities');
    }
}
