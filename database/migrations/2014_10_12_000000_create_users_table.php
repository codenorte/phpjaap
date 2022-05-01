<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            //$table->charset = 'utf8';
            $table->increments('id');

            //$table->id();

            $table->string('link')->unique()->nullable()->default(null);

            $table->unsignedBigInteger('roles_id')->nullable()->default(null);
            $table->foreign('roles_id')->references('id')->on('roles');
            
            $table->string('usuario',20)->nullable()->default(null);
            $table->string('password',200)->nullable()->default(null);
            $table->string('email',50)->nullable()->default(null);
            
            $table->integer('IDINSTITUCION')->unsigned();
            $table->foreign('IDINSTITUCION')->references('IDINSTITUCION')->on('institucion');

            $table->string('RUCCI',15);
            $table->string('NOMBRES',30);
            $table->string('APELLIDOS',30);
            $table->string('APADOSN',30)->nullable()->default(null);
            $table->text('DIRECCION',50)->nullable()->default(null);
            $table->string('TELEFONO',15)->nullable()->default(null);
            $table->string('CELULAR',15)->nullable()->default(null);
            $table->text('SECTOR',50)->nullable()->default(null);
            $table->text('REFERENCIA',100)->nullable()->default(null);
            
            $table->text('OBSERVACION',100)->nullable()->default(null);
            $table->string('ESTADO',10)->nullable()->default(null);
            $table->string('VISTO',2)->nullable()->default(null);

            $table->rememberToken();
            $table->string('api_token',60)->nullable()->default(null);
            

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
        Schema::dropIfExists('users');
    }
}
