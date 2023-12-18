<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("name")->nullable();
            $table->string("codice_fiscale")->nullable();
            $table->string("partita_iva")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("cap")->nullable();
            $table->string("province")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("website")->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
