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
			$table->string('name');
			$table->string('description', 2000);
			$table->string('photo');
			$table->string('video');
			$table->string('files');
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
