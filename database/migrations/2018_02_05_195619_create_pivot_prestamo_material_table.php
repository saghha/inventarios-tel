<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotPrestamoMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamo_material', function (Blueprint $table) {
            $table->unsignedInteger('id_prestamo');
            $table->unsignedInteger('id_material');
            $table->unsignedInteger('cantidad');
            $table->string('sku')->nullable();
            $table->unique(['id_prestamo','id_material','sku']);
            $table->softDeletes();
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
        Schema::dropIfExists('prestamo_material');
    }
}
