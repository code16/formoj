<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Section;
use Code16\Sharp\EntityList\Eloquent\SimpleEloquentReorderHandler;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Filters\HiddenFilter;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;

class FormojSectionSharpEntityList extends SharpEntityList
{
    public function buildList(EntityListFieldsContainer $fields): void
    {
        $fields
            ->addField(
                EntityListField::make("title")
                    ->setLabel(trans("formoj::sharp.sections.list.columns.title_label"))
            )
            ->addField(
                EntityListField::make("description")
                    ->setLabel(trans("formoj::sharp.sections.list.columns.description_label"))
            );
    }

    function buildListConfig(): void
    {
        $this
            ->configureReorderable(
                new SimpleEloquentReorderHandler(Section::class)
            );
    }

    protected function getFilters(): ?array
    {
        return [
            HiddenFilter::make('formoj_form')
        ];
    }

    public function getListData(): array|Arrayable
    {
        $sections = Section::orderBy("order")
            ->where("form_id", $this->queryParams->filterFor("formoj_form"));

        return $this
            ->setCustomTransformer("title", function($value, $instance) {
                return static::transformTitle($instance);
            })
            ->transform($sections->get());
    }

    public static function transformTitle(Section $section): string
    {
        return sprintf(
            '<div>%s</div><div><small>%s</small></div>',
            $section->title,
            $section->is_title_hidden
                ? trans("formoj::sharp.sections.list.data.title.is_hidden")
                : ""
        );
    }
}
