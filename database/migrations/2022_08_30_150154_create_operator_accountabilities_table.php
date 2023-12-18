<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorAccountabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_accountabilities', function (Blueprint $table) {
            $table->id();

            //?FK Operator One to One
            $table->foreignId("operator_id")->nullable()->constrained('operators')->nullOnDelete();
            $table->string('payments_sum')->default(0); // Somma del campo amount dei movimenti per ogni operatore
            $table->string('piecework')->default(0); //cottimo
            $table->string('unpaid')->default(0); //non pagato

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
        Schema::dropIfExists('operator_accountabilities');
    }
}
