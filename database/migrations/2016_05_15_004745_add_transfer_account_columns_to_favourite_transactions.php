<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferAccountColumnsToFavouriteTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favourite_transactions', function (Blueprint $table) {
            $table->integer('from_account_id')->unsigned()->index()->nullable();
            $table->foreign('from_account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->integer('to_account_id')->unsigned()->index()->nullable();
            $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favourite_transactions', function (Blueprint $table) {
            //
        });
    }
}
