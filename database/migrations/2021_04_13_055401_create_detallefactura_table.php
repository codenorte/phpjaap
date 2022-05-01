<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallefacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallefactura', function (Blueprint $table) {
            $table->increments('IDDETALLEFAC');

            $table->integer('IDTARIFAS')->unsigned();
            $table->foreign('IDTARIFAS')->references('IDTARIFAS')->on('tarifas');

            $table->integer('IDMEDIDOR')->unsigned();
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->string('ANIOMES')->nullable()->default(null);
            $table->integer('MEDIDAANT')->nullable()->default(null);
            $table->integer('MEDIDAACT')->nullable()->default(null);
            $table->integer('CONSUMO')->nullable()->default(null);
            $table->integer('MEDEXCEDIDO')->nullable()->default(null);

            $table->float('TAREXCEDIDO',8,2)->nullable()->default(null);
            $table->float('APORTEMINGA',8,2)->nullable()->default(null);
            $table->float('ALCANTARILLADO',8,2)->nullable()->default(null);
            $table->float('SUBTOTAL',8,2)->nullable()->default(null);
            $table->float('TOTAL',8,2)->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->integer('IDFACTURA')->unsigned()->nullable()->default(null);
            $table->foreign('IDFACTURA')->references('IDFACTURA')->on('facturas');

            $table->unsignedBigInteger('controlaniomes_id')->unsigned()->nullable()->default(null);
            $table->foreign('controlaniomes_id')->references('id')->on('controlaniomesdetallefacturas');

            $table->integer('NUMFACTURA')->nullable()->default(null);

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
        Schema::dropIfExists('detallefactura');
    }
}
