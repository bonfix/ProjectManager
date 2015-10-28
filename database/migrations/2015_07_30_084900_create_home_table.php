<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('home', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('sl_1_p', 100);
			$table->string('sl_1_h', 100);
			$table->string('sl_1_t', 2000);
			$table->string('sl_2_p', 100);
			$table->string('sl_2_h', 100);
			$table->string('sl_2_t', 2000);
			$table->string('sl_3_p', 100);
			$table->string('sl_3_h', 100);
			$table->string('sl_3_t', 2000);
			$table->string('sl_4_p', 100);
			$table->string('sl_4_h', 100);
			$table->string('sl_4_t', 2000);
			$table->string('sl_5_p', 100);
			$table->string('sl_5_h', 100);
			$table->string('sl_5_t', 2000);
			$table->string('row2_col1_panel1_h', 100);
			$table->string('row2_col1_panel1_l', 100);
			$table->string('row2_col1_panel1_p', 100);
			$table->string('row2_col1_panel1_t', 500);
			$table->string('row2_col2_panel1_h', 100);
			$table->string('row2_col2_panel1_l', 100);
			$table->string('row2_col2_panel1_p', 100);
			$table->string('row2_col2_panel1_t', 500);
			$table->string('row3_col1_panel1_h', 100);
			$table->string('row3_col1_panel1_l', 100);
			$table->string('row3_col1_panel1_p', 100);
			$table->string('row3_col1_panel1_t', 500);
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
		Schema::drop('home');
	}

}
