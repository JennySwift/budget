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
		Schema::create('transactions_tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('transaction_id')->unsigned(); //foreign key
			$table->integer('tag_id')->unsigned(); //foreign key
			$table->decimal('allocated_fixed', 10, 2)->nullable();
			$table->decimal('allocated_percent', 10, 2)->nullable();
			$table->decimal('calculated_allocation', 10, 2)->nullable();
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transactions_tags');
	}

}
