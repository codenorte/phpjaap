<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagopesilloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagopesillo', function (Blueprint $table) {
            $table->increments('IDPAGOPESILLO');

            $table->integer('IDASISTENCIAPESILLO')->unsigned();
            $table->foreign('IDASISTENCIAPESILLO')->references('IDASISTENCIAPESILLO')->on('asistenciapesillo');

            $table->date('FECHAPAGO')->nullable()->default(null);
            $table->integer('NUMMINGAS')->nullable()->default(null);
            $table->float('VALORMINGAS',8,2)->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);
            $table->integer('NUMFACTURA')->nullable()->default(null);
            $table->text('USUARIOACTUAL')->nullable()->default(null);

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
        Schema::dropIfExists('pagopesillo');
    }
}
