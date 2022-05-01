<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturatranspasoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturatranspaso', function (Blueprint $table) {
            $table->id();

            $table->integer('NUMFACTURA')->nullable()->default(null);
            $table->date('FECHAEMISION')->nullable()->default(null);
            $table->float('SUBTOTAL',8,2)->nullable()->default(null);
            $table->float('IVA',8,2)->nullable()->default(null);
            $table->float('TOTAL',8,2)->nullable()->default(null);

            $table->text('USUARIOACTUAL')->nullable()->default(null);

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
        Schema::dropIfExists('facturatranspaso');
    }
}
