<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('settings_id', 255)->unique();
            $table->string('settings_info_1', 255);
            $table->string('settings_info_2', 255);
            $table->timestamps();
        });

        Schema::table('settings', function (Blueprint $table) {
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
        Schema::dropIfExists('settings');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
