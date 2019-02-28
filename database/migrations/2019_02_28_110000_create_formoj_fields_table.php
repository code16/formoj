<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormojFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formoj_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string("label");
            $table->boolean('required')->default(false);
            $table->text("description")->nullable();
            $table->string("type"); // text, textarea, select

            // Text, textarea
            $table->unsignedSmallInteger("max_length")->nullable();

            // Select
            $table->longText("values")->nullable();
            $table->unsignedSmallInteger("max_values")->nullable();
            $table->boolean("multiple")->default(false);

            $table->unsignedInteger('section_id');
            $table->foreign('section_id')
                ->references('id')
                ->on('formoj_sections')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
