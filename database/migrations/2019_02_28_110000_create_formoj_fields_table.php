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
            $table->text("help_text")->nullable();
            $table->unsignedSmallInteger("order")->default(100);
            $table->string("type"); // text, textarea, select

            $table->longText("field_attributes");

//            // Text, textarea
//            $table->unsignedSmallInteger("max_length")->nullable();
//
//            // Textarea
//            $table->unsignedSmallInteger("rows_count")->nullable();
//
//            // Select
//            $table->longText("values")->nullable();
//            $table->unsignedSmallInteger("max_values")->nullable();
//            $table->boolean("multiple")->default(false);

            $table->unsignedInteger('section_id');
            $table->foreign('section_id')
                ->references('id')
                ->on('formoj_sections')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
