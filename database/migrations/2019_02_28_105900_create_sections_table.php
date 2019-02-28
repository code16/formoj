<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title");
            $table->text("description")->nullable();

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')
                ->on('forms')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
