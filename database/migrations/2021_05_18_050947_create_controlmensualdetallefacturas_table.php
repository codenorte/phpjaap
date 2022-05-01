<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlmensualdetallefacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controlmensualdetallefacturas', function (Blueprint $table) {
            $table->id();

            $table->integer('IDMEDIDOR')->nullable()->default(null);
            $table->date('ANIOMES')->nullable()->default(null);
            $table->string('estado')->nullable()->default(null);

            $table->integer('IDDETALLEFAC')->unsigned()->nullable()->default(null);
            $table->foreign('IDDETALLEFAC')->references('IDDETALLEFAC')->on('detallefactura');

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
        Schema::dropIfExists('controlmensualdetallefacturas');
    }
}
