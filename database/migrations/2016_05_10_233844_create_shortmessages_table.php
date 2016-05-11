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
			$table->string('from')->nullable()->index();
			$table->string('to')->nullable()->index();
			$table->string('message')->index();
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
