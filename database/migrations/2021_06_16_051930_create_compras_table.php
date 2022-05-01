<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();

            $table->string('numfactura')->nullable()->default(null);
            $table->dateTime('fechaemision')->nullable()->default(null);

            //$table->integer('cantidad')->nullable()->default(null);
            $table->float('subtotal',8,2)->nullable()->default(null);
            $table->float('iva',8,2)->nullable()->default(null);
            $table->float('total',8,2)->nullable()->default(null);
            //$table->float('total_compra',8,2)->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->unsignedBigInteger('proveedor_id')->nullable()->default(null);
            $table->foreign('proveedor_id')->references('id')->on('proveedor');

            //$table->unsignedBigInteger('materiales_id')->nullable()->default(null);
            //$table->foreign('materiales_id')->references('id')->on('materiales');

            $table->string('usuarioactual')->nullable()->default(null);
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
        Schema::dropIfExists('compras');
    }
}
