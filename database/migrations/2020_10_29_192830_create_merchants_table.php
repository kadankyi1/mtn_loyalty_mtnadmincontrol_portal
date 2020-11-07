<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->bigIncrements('merchant_id');
            $table->string('merchant_name', 255);
            $table->longText('merchant_location');
            $table->longText('merchant_scope');
            $table->string('merchant_phone_number', 255)->unique();
            $table->string('merchant_email', 255);
            $table->decimal('merchant_balance', 10, 2)->default('0');
            $table->string('merchant_pin', 255);
            $table->string('password', 255);
            $table->boolean('merchant_flagged');
            $table->timestamps();
        });

        Schema::table('merchants', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');

            $table->foreign('admin_id')->references('admin_id')->on('administrators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('merchants');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
