<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->increments('IDTARIFAS');

            $table->integer('BASE')->nullable()->default(null);
            $table->float('TARBASE',8,2)->nullable()->default(null);
            $table->float('APORTEMINGA',8,2)->nullable()->default(null);
            $table->text('DESCRIPCION')->nullable()->default(null);
            $table->float('VALOREXCESO',8,2)->nullable()->default(null);
            $table->float('ALCANTARRILLADO',8,2)->nullable()->default(null);
            $table->integer('IVA')->nullable()->default(null);

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
        Schema::dropIfExists('tarifas');
    }
}
