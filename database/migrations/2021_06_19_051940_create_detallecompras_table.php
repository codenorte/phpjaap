<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallecomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallecompras', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable()->default(null);
            $table->string('detalle')->nullable()->default(null);
            $table->string('codigo')->nullable()->default(null);

            $table->integer('cantidad')->nullable()->default(null);
            $table->float('precio',8,2)->nullable()->default(null);
            //$table->float('subtotal',8,2)->nullable()->default(null);
            $table->float('total',8,2)->nullable()->default(null);

            //$table->dateTime('fecha')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->unsignedBigInteger('compras_id')->nullable()->default(null);
            $table->foreign('compras_id')->references('id')->on('compras');

            //$table->unsignedBigInteger('proveedor_id')->nullable()->default(null);
            //$table->foreign('proveedor_id')->references('id')->on('proveedor');

            $table->unsignedBigInteger('materiales_id')->nullable()->default(null);
            $table->foreign('materiales_id')->references('id')->on('materiales');


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
        Schema::dropIfExists('detallecompras');
    }
}
