<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavouriteTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourite_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('name')->index();
            $table->integer('account_id')->unsigned()->index()->nullable();
            $table->enum('type', ['income', 'expense', 'transfer'])->index();
            $table->string('description')->nullable()->index()->nullable();
            $table->string('merchant')->nullable()->index()->nullable();
            $table->decimal('total', 10, 2)->index()->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('favourite_transactions');
    }
}
