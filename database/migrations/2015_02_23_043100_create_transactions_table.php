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

		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->timestamps();
			$table->date('date')->index();
			$table->string('type')->index();
			$table->string('description')->nullable()->index();
			$table->string('merchant')->nullable()->index();
			$table->decimal('total', 10, 2)->index();
			$table->integer('account_id')->unsigned()->index();
			$table->boolean('reconciled')->index();
			$table->boolean('allocated')->index();
			$table->integer('user_id')->unsigned()->index();

			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
