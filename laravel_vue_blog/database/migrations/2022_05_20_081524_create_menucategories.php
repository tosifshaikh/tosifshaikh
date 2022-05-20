<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenucategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menucategories', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->autoIncrement();
            $table->string('category_name')->nullable();
            $table->tinyInteger('is_active')->default(0)->comment('0-Inactive,1-Active');
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
        Schema::dropIfExists('menucategories');
    }
}
