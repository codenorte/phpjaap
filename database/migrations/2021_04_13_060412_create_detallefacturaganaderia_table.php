<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallefacturaganaderiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallefacturaganaderia', function (Blueprint $table) {
            $table->increments('IDDETALLEFACGANADERIA');

            $table->integer('IDTARIFASGANADERIA')->unsigned();
            $table->foreign('IDTARIFASGANADERIA')->references('IDTARIFASGANADERIA')->on('tarifasganaderia');

            $table->integer('IDAGUAGANADERIA')->unsigned();
            $table->foreign('IDAGUAGANADERIA')->references('IDAGUAGANADERIA')->on('aguaganaderia');
            
            $table->date('ANIOMES')->nullable()->default(null);
            $table->float('SUBTOTAL',8,2)->nullable()->default(null);
            $table->float('TOTAL',8,2)->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);
            $table->string('DETALLE')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->unsignedBigInteger('controlaniomesganaderia_id')->unsigned()->nullable()->default(null);
            $table->foreign('controlaniomesganaderia_id')->references('id')->on('controlaniomesganaderia');

            $table->integer('IDFACTURASGANADERIA')->unsigned()->nullable()->default(null);
            $table->foreign('IDFACTURASGANADERIA')->references('IDFACTURASGANADERIA')->on('facturasganaderia');
            
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
        Schema::dropIfExists('detallefacturaganaderia');
    }
}
