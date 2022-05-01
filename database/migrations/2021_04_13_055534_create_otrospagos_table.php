<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtrospagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otrospagos', function (Blueprint $table) {
            $table->increments('IDOTPAGOS');

            $table->integer('IDCORTE')->unsigned();
            $table->foreign('IDCORTE')->references('IDCORTE')->on('corte');

            $table->float('DERCONX',8,2)->nullable()->default(null);
            $table->float('MULRECX',8,2)->nullable()->default(null);
            $table->float('INTERES',8,2)->nullable()->default(null);
            $table->float('TOTAL',8,2)->nullable()->default(null);
            $table->integer('NUMFACTURA')->nullable()->default(null);
            $table->string('USUARIOACTUAL')->nullable()->default(null);
            $table->date('FECHAPAGO')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->integer('IDFACTURA')->unsigned()->nullable()->default(null);
            $table->foreign('IDFACTURA')->references('IDFACTURA')->on('facturas');

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
        Schema::dropIfExists('otrospagos');
    }
}
