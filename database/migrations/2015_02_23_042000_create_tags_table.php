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
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('tags', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->timestamps();
			$table->string('name');
			$table->decimal('fixed_budget', 10, 2)->nullable()->index();
			$table->decimal('flex_budget', 10, 2)->nullable()->index();
			$table->date('starting_date')->nullable()->index();
			$table->integer('budget_id')->nullable()->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();

			$table->foreign('budget_id')->references('id')->on('budgets');
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

		Schema::drop('tags');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
