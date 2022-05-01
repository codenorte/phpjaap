<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlaniomesdetallefacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controlaniomesdetallefacturas', function (Blueprint $table) {
            $table->id();
            
            $table->string('aniomes');
            $table->text('detalle')->nullable()->default(null);
            $table->string('conlectura')->nullable()->default(null);
            $table->string('sinlectura')->nullable()->default(null);

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
        Schema::dropIfExists('controlaniomesdetallefacturas');
    }
}
