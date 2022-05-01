<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corte', function (Blueprint $table) {
            $table->increments('IDCORTE');

            $table->integer('IDMEDIDOR')->unsigned();
            $table->foreign('IDMEDIDOR')->references('IDMEDIDOR')->on('medidor');

            $table->string('CORTE')->nullable()->default(null);
            $table->date('FECHA')->nullable()->default(null);
            $table->text('OBSERVACION')->nullable()->default(null);
            $table->float('MULTA',8,2)->nullable()->default(null);
            $table->integer('MORA')->nullable()->default(null);
            $table->string('PAGADO')->nullable()->default(null);

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
        Schema::dropIfExists('corte');
    }
}
