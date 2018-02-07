<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeingKeyResgistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestamo_material', function (Blueprint $table) {
            $table->foreign('id_material')->references('id')->on('materiales');
            $table->foreign('id_prestamo')->references('id')->on('prestamos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestamo_material', function (Blueprint $table) {
            $table->dropForeign(['id_material','id_prestamo']);
        });
    }
}
