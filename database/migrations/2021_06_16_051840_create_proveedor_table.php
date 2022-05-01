<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id();

            $table->string('ciruc')->nullable()->default(null);
            $table->string('nombres')->nullable()->default(null);
            $table->string('apellidos')->nullable()->default(null);
            $table->string('razon_social')->nullable()->default(null);
            $table->string('direccion')->nullable()->default(null);
            $table->string('celular')->nullable()->default(null);
            $table->string('telefono')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('pagina_web')->nullable()->default(null);
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
        Schema::dropIfExists('proveedor');
    }
}
