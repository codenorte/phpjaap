<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosasistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagosasistencia', function (Blueprint $table) {
            $table->increments('IDPAGOASISTENCIA');

            $table->integer('IDASISTENCIA')->unsigned();
            $table->foreign('IDASISTENCIA')->references('IDASISTENCIA')->on('asistencia');

            $table->date('FECHAPAGO')->nullable()->default(null);
            $table->integer('NUMMINGAS')->nullable()->default(null);
            $table->float('VALORMINGAS',8,2)->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);
            $table->string('USUARIOACTUAL')->nullable()->default(null);
            $table->integer('NUMFACTURA')->nullable()->default(null);

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
        Schema::dropIfExists('pagosasistencia');
    }
}
