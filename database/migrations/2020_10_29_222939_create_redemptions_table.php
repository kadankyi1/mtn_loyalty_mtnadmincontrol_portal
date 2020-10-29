<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->bigIncrements('redemption_id');
            $table->string('customer_phone', 255)->unique();
            $table->unsignedBigInteger('points_to_one_cedi_rate_used');
            $table->unsignedBigInteger('redeemed_points');
            $table->string('redemption_cedi_equivalent_paid', 255);
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('redemptions');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
