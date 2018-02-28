<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPrestamoMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestamo_material', function (Blueprint $table) {
            $table->dropColumn('id_prestamo');
            $table->dropColumn('id_material');
            
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
            $table->unsignedInteger('id_prestamo');
            $table->unsignedInteger('id_material');
        });
    }
}