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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('icon_id');
            $table->text('name');
            $table->text('phone_number');
            $table->double('lat');
            $table->double('long');
            $table->text('region');
            $table->text('street')->nullabe();
            $table->integer('build_number')->nullabe();
            $table->integer('house_number')->nullabe();
            $table->integer('floor_number')->nullabe();
            $table->text('note')->nullabe();
            $table->integer('code_id');
            $table->integer('country_id');
            $table->integer('city_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('addresses');
    }
};
