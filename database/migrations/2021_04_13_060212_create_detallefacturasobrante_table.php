<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallefacturasobranteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallefacturasobrante', function (Blueprint $table) {
            $table->increments('IDDETALLEFACSOBRANTE');

            $table->integer('IDTARIFASSOBRANTE')->unsigned();
            $table->foreign('IDTARIFASSOBRANTE')->references('IDTARIFASSOBRANTE')->on('tarifassobrante');

            $table->integer('IDAGUASOBRANTE')->unsigned();
            $table->foreign('IDAGUASOBRANTE')->references('IDAGUASOBRANTE')->on('aguasobrante');
            
            $table->string('ANIOMES')->nullable()->default(null);
            $table->float('SUBTOTAL',8,2)->nullable()->default(null);
            $table->float('TOTAL',8,2)->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);
            $table->string('DETALLE')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->unsignedBigInteger('controlaniomessobrante_id')->unsigned()->nullable()->default(null);
            $table->foreign('controlaniomessobrante_id')->references('id')->on('controlaniomessobrante');

            $table->integer('IDFACTURASOBRANTE')->unsigned()->nullable()->default(null);
            $table->foreign('IDFACTURASOBRANTE')->references('IDFACTURASOBRANTE')->on('facturassobrante'); 

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
        Schema::dropIfExists('detallefacturasobrante');
    }
}
