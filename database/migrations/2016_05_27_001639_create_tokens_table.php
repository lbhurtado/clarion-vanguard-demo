<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tokens', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code')->unique();
			$table->string('class')->index();
			$table->integer('reference')->unsigned()->index();
			$table->integer('quota')->unsigned()->nullable();
			$table->integer('contact_id')->unsigned()->nullable();
			$table->dateTime('claimed_at')->nullable();
			$table->timestamps();
			$table->unique(['class', 'reference']);
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tokens');
	}

}
