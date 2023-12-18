<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stones', function (Blueprint $table) {
            $table->id();
            $table->float("stone_weight", 10, 5);
            $table->float("client_cost", 8, 2); // Prezzo per il cliente
            $table->float("prezzariouser", 8, 2); // Prezzo per l'operatore

            //* FK stone classes - stone types - setting types
            $table->foreignId("stone_class_id")->nullable()->constrained('stone_classes')->nullOnDelete();
            $table->foreignId("stone_type_id")->nullable()->constrained('stone_types')->nullOnDelete();
            $table->foreignId("setting_type_id")->nullable()->constrained('setting_types')->nullOnDelete();

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
        Schema::dropIfExists('stones');
    }
}
