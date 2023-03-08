<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datatable', function (Blueprint $table) {
            $table->id('datatable_id')->unsigned()->autoIncrement();
            $table->string('table');
            $table->string('join');
            $table->string('where');
            $table->string('orderBy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datatable');
    }
};
