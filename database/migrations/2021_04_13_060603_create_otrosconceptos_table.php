<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtrosconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otrosconceptos', function (Blueprint $table) {
            $table->id();

            $table->string('DESCRIPCION')->nullable()->default(null);
            $table->float('CANTIDAD',8,2)->nullable()->default(null);
            $table->integer('TIEMPO')->nullable()->default(null);
            $table->string('ACTIVO')->nullable()->default(null);

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
        Schema::dropIfExists('otrosconceptos');
    }
}
