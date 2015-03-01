<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budgets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('type');
		});

		Schema::create('tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->decimal('fixed_budget', 10, 2)->nullable();
			$table->decimal('flex_budget', 10, 2)->nullable();
			$table->date('starting_date')->nullable();
			$table->integer('budget_id')->nullable()->unsigned(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('budget_id')->references('id')->on('budgets');
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
		Schema::drop('tags');
		Schema::drop('budgets');
	}

}
