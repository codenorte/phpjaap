<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanificacionpesilloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacionpesillo', function (Blueprint $table) {
            $table->increments('IDPLANIFICACIONPESILLO');

            $table->text('LUGAR')->nullable()->default(null);
            $table->date('FECHA')->nullable()->default(null);
            $table->float('VALORMULTA',8,2)->nullable()->default(null);
            $table->text('DESCRIPCION')->nullable()->default(null);
            $table->string('TIPOPLANPESILLO')->nullable()->default(null);

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
        Schema::dropIfExists('planificacionpesillo');
    }
}
