<?php

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Field;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddFieldIdentifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formoj_fields', function (Blueprint $table) {
            if (config("app.env") != "testing") {
                $table->string("identifier");
            } else {
                $table->string("identifier")->default('');
            }
        });

        $this->fillEmptyFieldIdentifiers();
        $this->replaceAnswerFieldsWithIdentifiers();
    }

    public function fillEmptyFieldIdentifiers()
    {
        Field::where('identifier','=','')
            ->get()
            ->each(function(Field $field) {
                $slug = $field->label ? Str::slug($field->label,'_') : 'unnamed_field';
                $generatedIdentifier = $slug;
                $i = 1;

                while(
                    Field::join('formoj_sections','formoj_sections.id','=','section_id')
                        ->where('form_id','=',$field->section->form->id)
                        ->where('formoj_fields.identifier','=',$generatedIdentifier)
                        ->exists()
                ) {
                    $generatedIdentifier = $slug . '_' . $i;
                    $i++;
                }

                $field->identifier = $generatedIdentifier;
                $field->save();
            });
    }

    public function replaceAnswerFieldsWithIdentifiers()
    {
        Answer::get()
            ->each(function(Answer $answer) {
                $newContent = [];

                foreach($answer->content as $key => $value) {
                    $field = Field::join('formoj_sections','formoj_fields.section_id','=','formoj_sections.id')
                        ->where('form_id','=',$answer->form_id)
                        ->where('label','=',$key)
                        ->first();

                    if($field) {
                        $newContent[$field->identifier] = $value;
                    } else {
                        $newContent[$key] = $value;
                    }

                }
                $answer->content = $newContent;
                $answer->save();
            });
    }
}
