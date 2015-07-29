<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('transactions_tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('transaction_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->decimal('allocated_fixed', 10, 2)->nullable();
			$table->decimal('allocated_percent', 10, 2)->nullable();
			$table->decimal('calculated_allocation', 10, 2)->nullable();

			$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
		});

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::drop('transactions_tags');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
