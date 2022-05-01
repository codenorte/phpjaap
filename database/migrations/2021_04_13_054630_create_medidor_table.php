<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedidorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidor', function (Blueprint $table) {
            $table->increments('IDMEDIDOR');

            $table->integer('IDUSUARIO')->unsigned();
            $table->foreign('IDUSUARIO')->references('id')->on('users')->onDelete('cascade');

            $table->string('SERIE')->nullable()->default(null);
            $table->integer('NUMMEDIDOR')->nullable()->default(null);
            $table->integer('CODIGO')->nullable()->default(null);
            $table->string('ESTADO')->nullable()->default(null);
            $table->float('VALORPORCONEXION',8,2)->nullable()->default(null);
            $table->string('PAGADO')->nullable()->default(null);
            $table->float('SALDO',8,2)->nullable()->default(null);
            $table->date('FECHA')->nullable()->default(null);

            $table->string('visto')->nullable()->default(null);


            $table->unsignedBigInteger('detallemat_id')->nullable()->default(null);
            $table->foreign('detallemat_id')->references('id')->on('detallemat');

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
        Schema::dropIfExists('medidor');
    }
}
