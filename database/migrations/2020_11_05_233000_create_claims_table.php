<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('claim_id');
            $table->decimal('claim_amount', 10, 2);
            $table->boolean('paid_status')->default('0');
            $table->boolean('claim_flagged');
            $table->unsignedBigInteger('payer_admin_id')->default('0');
            $table->timestamps();
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('merchant_id')->on('merchants');
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
        Schema::dropIfExists('claims');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
