<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasganaderiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifasganaderia', function (Blueprint $table) {
            $table->increments('IDTARIFASGANADERIA');

            $table->float('TARIFAMENSUAL',8,2)->nullable()->default(null);
            $table->string('DESCRIPCION')->nullable()->default(null);
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
        Schema::dropIfExists('tarifasganaderia');
    }
}
