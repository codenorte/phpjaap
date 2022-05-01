<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('IDFACTURA');

            $table->integer('IDDETALLEFAC')->nullable()->default(null);
            //$table->foreign('IDDETALLEFAC')->references('IDDETALLEFAC')->on('detallefactura');
            
            $table->integer('NUMFACTURA')->nullable()->default(null);
            $table->date('FECHAEMISION')->nullable()->default(null);
            $table->float('SUBTOTAL',8,2)->nullable()->default(null);
            $table->float('IVA',8,2)->nullable()->default(null);
            $table->float('TOTAL',8,2)->nullable()->default(null);
            $table->text('USUARIOACTUAL')->nullable()->default(null);

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
        Schema::dropIfExists('facturas');
    }
}
