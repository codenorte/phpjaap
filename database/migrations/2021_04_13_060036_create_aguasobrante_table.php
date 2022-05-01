<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAguasobranteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aguasobrante', function (Blueprint $table) {
            $table->increments('IDAGUASOBRANTE');

            $table->integer('IDUSUARIO')->unsigned();
            $table->foreign('IDUSUARIO')->references('id')->on('users');

            $table->string('SECTOR')->nullable()->default(null);
            $table->string('REFERENCIA')->nullable()->default(null);
            $table->integer('CODIGOAGUASOBRANTE')->nullable()->default(null);
            $table->string('OBSERVACION')->nullable()->default(null);
            $table->string('ESTADO')->nullable()->default(null);
            $table->float('VALORPORCONEXION',8,2)->nullable()->default(null);
            $table->integer('PAGADO')->nullable()->default(null);
            $table->float('SALDO',8,2)->nullable()->default(null);
            $table->date('FECHA')->nullable()->default(null);

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
        Schema::dropIfExists('aguasobrante');
    }
}
