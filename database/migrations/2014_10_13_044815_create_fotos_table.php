<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->string('thumbnail')->nullable()->default(null);
            $table->string('imagelink')->nullable()->default(null);

            $table->integer('IDUSUARIO')->unsigned();
            $table->foreign('IDUSUARIO')->references('id')->on('users')->onDelete('cascade');

            $table->string('estado');

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
        Schema::dropIfExists('fotos');
    }
}
