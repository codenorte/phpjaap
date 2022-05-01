<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitucionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institucion', function (Blueprint $table) {
            $table->increments('IDINSTITUCION');

            $table->text('NOMBREINST');
            $table->text('DIRECCION');
            $table->string('TELEFONO')->nullable()->default(null);
            $table->string('EMAIL')->nullable()->default(null);
            $table->string('RUC');
            $table->string('CELULAR')->nullable()->default(null);
            $table->string('LOGO')->nullable()->default(null);
            $table->string('ESTADO')->nullable()->default(null);

            $table->string('PAGINAWEB')->nullable()->default(null);

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
        Schema::dropIfExists('institucion');
    }
}
