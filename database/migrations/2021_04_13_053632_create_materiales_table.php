<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable()->default(null);
            $table->string('detalle')->nullable()->default(null);
            $table->string('codigo')->nullable()->default(null);
            $table->integer('stock')->nullable()->default(null);
            $table->integer('total')->nullable()->default(null);
            $table->string('estado')->nullable()->default(null);

            //$table->unsignedBigInteger('tipomat_id')->nullable()->default(null);

            $table->unsignedBigInteger('categoriasmat_id')->nullable()->default(null);
            $table->foreign('categoriasmat_id')->references('id')->on('categoriasmat');

            //$table->integer('compras_id')->nullable()->default(null);

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
        Schema::dropIfExists('materiales');
    }
}
