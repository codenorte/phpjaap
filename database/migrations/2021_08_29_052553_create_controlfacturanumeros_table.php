<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlfacturanumerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controlfacturanumeros', function (Blueprint $table) {
            $table->id();
            $table->string('TABLE')->nullable()->default(null);
            $table->integer('TABLE_ID')->nullable()->default(null);
            $table->integer('NUMFACTURA')->nullable()->default(null);
            $table->date('FECHA')->nullable()->default(null);
            $table->string('ESTADO')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('controlfacturanumeros');
    }
}
