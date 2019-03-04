<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormojAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formoj_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->longText("content");

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')
                ->on('formoj_forms')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
