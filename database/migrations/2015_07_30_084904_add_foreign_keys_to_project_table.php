<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProjectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project', function(Blueprint $table)
		{
			$table->foreign('category_id', 'category_id_fk_project')->references('id')->on('category')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id', 'user_id_fk_project')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project', function(Blueprint $table)
		{
			$table->dropForeign('category_id_fk_project');
			$table->dropForeign('user_id_fk_project');
		});
	}

}
