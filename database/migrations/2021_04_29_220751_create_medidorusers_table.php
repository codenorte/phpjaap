<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedidorusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidorusers', function (Blueprint $table) {
            $table->id();

            $table->dateTime('FECHA')->nullable()->default(null);

            $table->integer('IDUSUARIO')->unsigned();
            $table->foreign('IDUSUARIO')->references('id')->on('users')->onDelete('cascade');
            
            $table->integer('IDUSUARIO_HIJO')->unsigned()->nullable()->default(null);
            $table->foreign('IDUSUARIO_HIJO')->references('id')->on('users')->onDelete('cascade');

            $table->integer('IDMEDIDOR')->unsigned()->nullable()->default(null);
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->string('ESTADO')->nullable()->default(null);
            $table->integer('NIVEL')->nullable()->default(null);

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
        Schema::dropIfExists('medidorusers');
    }
}
