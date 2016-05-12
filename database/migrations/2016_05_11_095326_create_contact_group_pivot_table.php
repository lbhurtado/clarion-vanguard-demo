<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactGroupPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_group', function (Blueprint $table) {
            $table->integer('contact_id')->unsigned()->index();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->primary(['contact_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contact_group');
    }
}
