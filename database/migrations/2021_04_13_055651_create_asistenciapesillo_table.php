<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciapesilloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistenciapesillo', function (Blueprint $table) {
            $table->increments('IDASISTENCIAPESILLO');

            $table->integer('IDMEDIDOR')->unsigned();
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->integer('IDPLANIFICACIONPESILLO')->unsigned();
            $table->foreign('IDPLANIFICACIONPESILLO')->references('IDPLANIFICACIONPESILLO')->on('planificacionpesillo');

            $table->string('ASISTENCIA')->nullable()->default(null);
            $table->float('VALORMULTA',8,2)->nullable()->default(null);
            $table->string('DESCRIPCION')->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);

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
        Schema::dropIfExists('asistenciapesillo');
    }
}
