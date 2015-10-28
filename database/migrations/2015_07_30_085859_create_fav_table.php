<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFavTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fav', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('user_id_fk_fav');
			$table->integer('project_id')->index('project_id_fk_fav');
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
		Schema::drop('fav');
	}

}
