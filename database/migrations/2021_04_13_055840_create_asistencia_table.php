<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->increments('IDASISTENCIA');

            $table->integer('IDPLANIFICACION')->unsigned();
            $table->foreign('IDPLANIFICACION')->references('IDPLANIFICACION')->on('planificacion');

            $table->integer('IDMEDIDOR')->unsigned();
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->string('ASISTENCIA')->nullable()->default(null);
            $table->float('VALORMULTA',8,2)->nullable()->default(null);
            $table->string('DESCRIPCION')->nullable()->default(null);
            $table->string('OBSEVACION')->nullable()->default(null);

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
        Schema::dropIfExists('asistencia');
    }
}
