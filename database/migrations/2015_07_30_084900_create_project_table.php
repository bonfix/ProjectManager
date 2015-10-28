<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 11);
			$table->string('description', 11);
			$table->string('photo', 11);
			$table->string('video', 11);
			$table->string('files', 11);
			$table->integer('user_id')->index('user_id_fk_project');
			$table->integer('category_id')->index('category_id_fk_project');
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
		Schema::drop('project');
	}

}
