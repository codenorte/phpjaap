<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialfacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historialfactura', function (Blueprint $table) {
            $table->id();

            $table->integer('idUsuario')->nullable()->default(null);
            $table->integer('numFact')->nullable()->default(null);
            $table->dateTime('fecha')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historialfactura');
    }
}
