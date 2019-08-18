<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->integer('user_id');
            $table->float('AUD', 12, 2);
            $table->float('CHF', 12, 2);
            $table->float('EUR', 12, 2);
            $table->float('GBP', 12, 2);
            $table->float('JPY', 12, 2);
            $table->float('USD', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
