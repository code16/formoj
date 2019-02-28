<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title");
            $table->text("description")->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('unpublished_at')->nullable();

            $table->timestamps();
        });
    }
}
