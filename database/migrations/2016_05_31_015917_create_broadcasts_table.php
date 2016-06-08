<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('broadcasts', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('pending_id')->unsigned()->index();
			$table->string('from')->nullable()->index();
			$table->string('to')->index();
			$table->text('message');
            $table->timestamps();
			$table->softDeletes();
			$table->unique(['pending_id', 'to']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('broadcasts');
	}

}
