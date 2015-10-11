<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsFavouriteTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets_favourite_transactions', function (Blueprint $table) {
            $table->integer('favourite_transaction_id')->unsigned()->index();
            $table->integer('budget_id')->unsigned()->index();
//            $table->decimal('allocated_fixed', 10, 2)->nullable();
//            $table->decimal('allocated_percent', 10, 2)->nullable();
//            $table->decimal('calculated_allocation', 10, 2)->nullable();

            $table->foreign('favourite_transaction_id')->references('id')->on('favourite_transactions')->onDelete('cascade');
            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('budgets_favourite_transactions');
    }
}
