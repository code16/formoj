![Formoj for Laravel](docs/img/logo.png)

Formoj for Laravel is a form generator package.

![](/docs/img/formoj.png)  
*Example of a Formoj form (with de default style).*

You can picture it like a small Google Form but with full control on it. Formoj takes care of the form storage and display, allows an administrator to manage forms (notifications, export answers in XLS format) and, of course, stores answers.

The project is separated in 3 modules:

 - the Vue-based front package, which is a distinct NPM package (see installation instructions below)
 - the backend code: models and migrations, controllers, jobs, notifications, ...
 - and finally an optional [Sharp-based](https://sharp.code16.fr) administration tool, to manage forms, sections, fields and export answers. 

## Installation

### Vue plugin
```sh
npm install formoj
```

#### Basic
```js
import Vue from 'vue';
import Formoj from 'formoj';

Vue.use(Formoj);
```

#### Advanced Configuration
```js
Vue.use(Formoj, {
    apiBaseUrl: '/custom/api',
    scrollOffset: 160,
    locale: 'en',
    i18n: {
      en: {
        'section.button.next': 'Next section',
      }
    }
});
```

| config | description |
|---|---|
| apiBaseUrl | Base URL of the formoj API (define it as `base_url` in laravel in `config/formoj.php`)
| scrollOffset | Add offset to the automatic scroll top behavior, useful when there is a fixed header in the site.
| locale | Locale used in all forms messages, buttons, etc... If not set, the `<html lang="en">` attribute is used.
| i18n | Localized messages to customize default formoj i18n messages, see [`lang`](https://github.com/code16/formoj/tree/master/packages/formoj/src/lang) files

### Laravel module

Formoj requires Laravel 7+, Carbon 2.0+, and maatwebsite/excel 3.1+.

Install the package via composer:

```sh
composer require code16/formoj
```

Then run this to create the needed tables in the database:

```shell
php artisan migrate
```

You may publish the config file:

```shell
php artisan vendor:publish --provider="Code16\Formoj\FormojServiceProvider" --tag="config"
```

And the lang file, if you need to update or add a translation (consider a PR in this case):

```shell
php artisan vendor:publish --provider="Code16\Formoj\FormojServiceProvider" --tag="lang"
```

### Update Formoj

Warning: when updating Formoj with `composer update code16/formoj`, **be sure to also execute** `npm install formoj` to keep back and front code in line. 

## Create a form

### With Sharp

If your project is already using [Sharp 6 for Laravel](https://code16.sharp.fr), great. If not, and if you want to use it with Formoj, first [install the package](https://sharp.code16.fr/docs/guide/#installation).

Then we need to configure Formoj in `config/sharp.php`:

```php
return [
    // config/sharp.php

    [...],

    "entities" => [
        "formoj_form" => [
            "label" => "Form",
            "list" => \Code16\Formoj\Sharp\FormojFormSharpEntityList::class,
            "show" => \Code16\Formoj\Sharp\FormojFormSharpShow::class,
            "form" => \Code16\Formoj\Sharp\FormojFormSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFormSharpValidator::class,
        ],
        "formoj_section" => [
            "label" => "Section",
            "list" => \Code16\Formoj\Sharp\FormojSectionSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojSectionSharpForm::class,
            "show" => \Code16\Formoj\Sharp\FormojSectionSharpShow::class,
            "validator" => \Code16\Formoj\Sharp\FormojSectionSharpValidator::class,
        ],
        "formoj_field" => [
            "label" => "Field",
            "list" => \Code16\Formoj\Sharp\FormojFieldSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojFieldSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFieldSharpValidator::class,
        ],
        "formoj_answer" => [
            "label" => "Answer",
            "list" => \Code16\Formoj\Sharp\FormojAnswerSharpEntityList::class,
            "show" => \Code16\Formoj\Sharp\FormojAnswerSharpShow::class,
            "policy" => \Code16\Formoj\Sharp\Policies\FormojAnswerSharpPolicy::class,
        ],
        "formoj_reply" => [
            "list" => \Code16\Formoj\Sharp\FormojReplySharpEntityList::class,
            "policy" => \Code16\Formoj\Sharp\Policies\FormojReplySharpPolicy::class,
        ],
    ],

    "menu" => [
        [
            "label" => "Formoj",
            "entities" => [
                [
                    "entity" => "formoj_form",
                    "label" => "Forms",
                    "icon" => "fa-list-alt"
                ]
            ]
        ]
    ],

    [...]
];
```

You can of course adapt this, depending on your needs: use our own subclasses, tweak the menu... 

This will add a full Formoj administration in Sharp:

![](/docs/img/sharp-01.png)

![](/docs/img/sharp-02.png)

![](/docs/img/sharp-03.png)

![](/docs/img/sharp-04.png)

![](/docs/img/sharp-05.png)

### Without Sharp

Formoj does not provide any other admin tool for now, so in this case, well, you're free to do as you want. 

There are a couple methods to help you handle section and field creation, for instance:

```php
$section = $form->createSection("My section");

$text = $section->newTextField("text")
                ->setRequired()
                ->setHelpText("help")
                ->setMaxLength(50)
                ->create();
                     
$select = $section->newSelectField("select", ["option A", "option B"])
                  ->setRequired()
                  ->setHelpText("help")
                  ->setMultiple()->setMaxOptions(2)
                  ->create();
```

## Manage a form

Once again, with Sharp you'll find all the appropriate tooling. This section describes 

### Form availability

A form can optionally defined a `published_at` and/or a `unpublished_at` dates: outside this period, the form is displayed with an adapted message.

### Form notifications

The form administrator can choose (for each form) to be notified by mail at `formoj_form.administrator_email` in two ways:

- `formoj_form.notifications_strategy` = "every": after every answer
- `formoj_form.notifications_strategy` = "grouped": a summary of answers of one given day is sent daily.

To configure the "grouped" strategy, you'll have to schedule the `Code16\Formoj\Console\SendFormojNotificationsForYesterday` Command, which will send all answers of yesterday. 

### Form sections

A Form is made of sections, which contains fields. If the form contains more than one section, it will be displayed one section at a time, with "Next" button.

### Field types

Formoj can display these types of fields:

- `text`, which is a single line text, with an optional `max_length` constraint
- `textarea`, with a and `rows_count` and an optional `max_length` 
- `select`, which can either be `multiple` (checklist, with an optional `max_options` attribute) or not (single select).
- `upload`, with a `max_size` expressed in MB and an optional allowed extension list named `accept` (upload and storage directory and disk are configurable in `config/formoj.php`)
- and finally `heading`, which is not a field, but a text separator.

## Embed a form

A given form can then be embedded anywhere with this DOM:

```php
<formoj form-id="1"></formoj>
```

## Work with answers

In addition to the notification system, the form administrator can export answers at any time on a xlsx format, via the `Code16\Formoj\Job\ExportAnswersToXls` job, which can be called this way:

```php
ExportAnswersToXls::dispatch($form, $fileName, $answers);
```

Where:

- `$form` is a `Code16\Formoj\Models\Form` instance
- `$fileName` is the export file name (export directory and disk are configurable in `config/formoj.php`)
- and `$answers` is a Collection of `Code16\Formoj\Models\Answers`; this argument is nullable, all answers of `$form` are exported by default.

Is Sharp is configured, it will provide a dedicated Command to handle this export (as well as one to display an answer).

## Styling the form

Formoj uses SASS/SCSS language for styles. You can import the style with sass import:

```scss
@import 'formoj/scss/themes/default';
```
The default theme is very basic and is meant to be customized by your own code.
Some variable are available and can be overridden, see [`formoj/scss/_variables.scss`](https://github.com/code16/formoj/blob/master/packages/formoj/scss/_variables.scss)

### Bootstrap integration

```scss
@import 'formoj/scss/themes/bootstrap';

$formoj-form-appearance: 'card';
$formoj-loading-appearance: 'spinner';
```

The bootstrap theme bind all bootstrap's form classes to formoj elements. By default the form has a `card` appearance, you may want to reset that behavior.

```scss
$formoj-form-appearance: 'none';
```

In addition, the select and checkboxes can have the bootstrap's `custom-control` style

```scss
$formoj-select-appearance: 'custom';
```

## Contributing

Setup prototipoj
```
    cd ./prototipoj
    composer install
```

Build front-end
```sh
    cd ./prototipoj
    npm install

    # Watch and auto re-build formoj package files
    npm run watch

    # Build all dist files
    npm run prod
```

