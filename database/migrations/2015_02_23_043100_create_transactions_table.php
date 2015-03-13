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
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('date');
			$table->string('type');
			$table->string('description')->nullable();
			$table->string('merchant')->nullable();
			$table->decimal('total', 10, 2);
			$table->integer('account_id')->unsigned(); //foreign key
			$table->boolean('reconciled');
			$table->boolean('allocated');
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('account_id')->references('id')->on('accounts');
			$table->foreign('user_id')->references('id')->on('users');	
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

		Schema::drop('transactions');
		Schema::drop('accounts');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
