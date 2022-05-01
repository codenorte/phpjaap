<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallematTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallemat', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable()->default(null);
            $table->string('detalle')->nullable()->default(null);
            $table->string('codigo')->nullable()->default(null);
            $table->string('serial')->nullable()->default(null);
            $table->string('estado')->nullable()->default(null);

            $table->integer('detallecompras_id')->nullable()->default(null);
            
            $table->unsignedBigInteger('materiales_id');
            $table->foreign('materiales_id')->references('id')->on('materiales');

            $table->unsignedBigInteger('tipomat_id');
            $table->foreign('tipomat_id')->references('id')->on('tipomat');


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
        Schema::dropIfExists('detallemat');
    }
}
