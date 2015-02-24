<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('date');
			$table->string('type');
			$table->string('description')->nullable();
			$table->string('merchant')->nullable();
			$table->decimal('total', 10, 2);
			$table->integer('account')->unsigned(); //foreign key
			$table->boolean('reconciled');
			$table->boolean('allocated');
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('account')->references('id')->on('accounts');
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
		Schema::drop('transactions');
	}

}
