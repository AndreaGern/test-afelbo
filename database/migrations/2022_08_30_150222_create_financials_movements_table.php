<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialsMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials_movements', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("movementable_id");
            $table->string("movementable_type");

            $table->string('causal');
            $table->string('amount');

            //? FK COMMISSION - Ogni pagamento del cliente é riferito ad una commission specifica. 
            $table->foreignId('commission_id')->nullable()->constrained('commissions')->nullOnDelete();
            
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
        Schema::dropIfExists('financials_movements');
    }
}
