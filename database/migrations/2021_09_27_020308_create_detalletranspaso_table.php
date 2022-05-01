<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalletranspasoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalletranspaso', function (Blueprint $table) {
            $table->id();

            $table->integer('cantidad')->nullable()->default(null);
            $table->string('detalle')->nullable()->default(null);
            $table->float('precio',8,2)->nullable()->default(null);
            $table->float('subtotal',8,2)->nullable()->default(null);

            $table->unsignedBigInteger('IDMATERIALES')->nullable()->default(null);
            $table->foreign('IDMATERIALES')->references('id')->on('materiales');

            $table->unsignedBigInteger('IDTRANSPASO')->nullable()->default(null);
            $table->foreign('IDTRANSPASO')->references('id')->on('transpaso');

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
        Schema::dropIfExists('detalletranspaso');
    }
}
