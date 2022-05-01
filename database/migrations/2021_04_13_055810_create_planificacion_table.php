<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanificacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion', function (Blueprint $table) {
            $table->increments('IDPLANIFICACION');

            $table->string('TIPOPLANIFICACION')->nullable()->default(null);
            $table->text('LUGAR')->nullable()->default(null);
            $table->date('FECHA')->nullable()->default(null);
            $table->float('VALORMULTA',8,2)->nullable()->default(null);
            $table->text('DESCRIPCION')->nullable()->default(null);

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
        Schema::dropIfExists('planificacion');
    }
}
