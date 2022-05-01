<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->increments('IDINVENTARIO');

            $table->string('CODARTICULO')->nullable()->default(null);
            $table->text('DESCRIPCION')->nullable()->default(null);
            $table->date('FECHAADQUISICION')->nullable()->default(null);
            $table->float('VALOR',8,2)->nullable()->default(null);
            $table->string('DEPRECIABLE')->nullable()->default(null);
            $table->text('NOMBRE')->nullable()->default(null);
            $table->integer('CANTIDAD')->nullable()->default(null);
            $table->binary('IMAGEN')->nullable()->default(null);

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
        Schema::dropIfExists('inventario');
    }
}
