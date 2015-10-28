<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comment', function(Blueprint $table)
		{
			$table->foreign('project_id', 'project_id_fk_comment')->references('id')->on('project')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id', 'user_id_fk_comment')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comment', function(Blueprint $table)
		{
			$table->dropForeign('project_id_fk_comment');
			$table->dropForeign('user_id_fk_comment');
		});
	}

}
