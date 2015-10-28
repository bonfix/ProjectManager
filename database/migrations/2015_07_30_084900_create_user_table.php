<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 11);
			$table->string('password', 11);
			$table->string('email', 11)->unique('email');
			$table->string('phone', 11);
			$table->integer('account_type');
			$table->timestamp('last_login')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('login_attempts');
			$table->integer('status');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}
