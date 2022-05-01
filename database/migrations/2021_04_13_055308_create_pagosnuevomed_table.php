<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosnuevomedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagosnuevomed', function (Blueprint $table) {
            $table->increments('IDNUEVOMED');

            $table->integer('IDMEDIDOR')->unsigned()->nullable()->default(null);
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->string('OBSERCION')->nullable()->default(null);
            $table->float('CANTIDADPAGAR',8,2)->nullable()->default(null);
            $table->date('FECHAPAGO')->nullable()->default(null);
            $table->integer('NUMFACT')->nullable()->default(null);

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
        Schema::dropIfExists('pagosnuevomed');
    }
}
