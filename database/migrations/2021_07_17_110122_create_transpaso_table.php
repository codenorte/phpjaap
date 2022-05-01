<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranspasoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transpaso', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_transpaso')->nullable()->default(null);
            $table->string('detalle')->nullable()->default(null);
            $table->string('estado')->nullable()->default(null);

            //$table->integer('IDMATERIALES')->unsigned();
            //$table->foreign('IDMATERIALES')->references('id')->on('materiales');

            $table->integer('IDMEDIDOR')->unsigned();
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

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
        Schema::dropIfExists('transpaso');
    }
}
