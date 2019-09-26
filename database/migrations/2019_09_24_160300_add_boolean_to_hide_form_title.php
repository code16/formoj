<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBooleanToHideFormTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formoj_forms', function (Blueprint $table) {
            $table->boolean('is_title_hidden')->default(false);
        });
    }
}
