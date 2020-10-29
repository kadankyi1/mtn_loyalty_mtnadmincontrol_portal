<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->bigIncrements('admin_id');
            $table->string('admin_surname', 255);
            $table->string('admin_firstname', 255);
            $table->string('admin_othernames', 255)->nullable();
            $table->string('admin_phone_number', 255)->unique();
            $table->string('admin_email', 255)->unique();
            $table->string('admin_pin', 255);
            $table->string('password', 255);
            $table->text('admin_scope');
            $table->boolean('admin_flagged');
            $table->unsignedBigInteger('creator_admin_id')->nullable();
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
        Schema::dropIfExists('administrators');
    }
}
