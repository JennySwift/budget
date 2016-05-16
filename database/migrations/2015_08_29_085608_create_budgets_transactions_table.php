<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budgets_transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('transaction_id')->unsigned()->index();
			$table->integer('budget_id')->unsigned()->index();
			$table->decimal('allocated_fixed', 10, 2)->nullable();
			$table->decimal('allocated_percent', 10, 2)->nullable();
			$table->decimal('calculated_allocation', 10, 2)->default('0.00');

			$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
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
		Schema::drop('budgets_transactions');
	}

}
