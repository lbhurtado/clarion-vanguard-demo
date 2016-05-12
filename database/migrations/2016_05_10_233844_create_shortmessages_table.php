<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortmessagesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('short_messages', function(Blueprint $table) {
            $table->increments('id');
			$table->string('from')->index();
			$table->string('to')->index();
			$table->text('message');
			$table->tinyInteger('direction');
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
		Schema::drop('short_messages');
	}

}
