<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallefacturainstalacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallefacturainstalacion', function (Blueprint $table) {
            $table->increments('IDDETALLEFAC');
            
            $table->integer('IDMEDIDOR')->unsigned();
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->float('TOTAL',8,2)->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);

            $table->string('estado')->nullable()->default(null);

            $table->integer('IDFACTURA')->unsigned()->nullable()->default(null);
            $table->foreign('IDFACTURA')->references('IDFACTURA')->on('facturasinstalacion');

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
        Schema::dropIfExists('detallefacturainstalacion');
    }
}
