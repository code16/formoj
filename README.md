![Formoj for Laravel](docs/img/logo.png)

Formoj for Laravel is a form generator package.

![](/docs/img/formoj.png)  
*Example of a Formoj form (with de default style).*

I guess you can picture it like a small Google Form but with full control on it. Formoj takes care of the form storage and display, allows an administrator to manage forms (notifications, export answers in XLS format) and, of course, stores answers.

The project is separated in 3 modules:

 - the Vue-based front package, which is a distinct NPM package (see installation instructions below)
 - the backend code: models and migrations, controllers, jobs, notifications, ...
 - and finally an optional [Sharp-based](https://github.com/code16/sharp) administration tool, to manage forms, sections, fields and export answers. 

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
});
```

| config | description |
|---|---|
| apiBaseUrl | Base URL of the formoj API (define it as `base_url` in laravel in `config/formoj.php`)
| scrollOffset | Add offset to the automatic scroll top behavior, useful when there is a fixed header in the site.
| locale | Locale used in all forms messages, buttons, etc... If not set, the `<html lang="en">` attribute is used.

### Laravel module

Formoj requires Laravel 5.8+, and Carbon 2.0+.

Install the package via composer:

```sh
composer require code16/formoj
```

Then run this to create the needed tables in the database:

```php
php artisan migrate
```

You may publish the config file:

```php
php artisan vendor:publish --provider="Code16\Formoj\FormojServiceProvider" --tag="config"
```

And the lang file, if you need to update or add a translation (consider a PR in this case):

```php
php artisan vendor:publish --provider="Code16\Formoj\FormojServiceProvider" --tag="lang"
```

## Create a form

### With Sharp

If your project is already using [Sharp for Laravel](https://github.com/code16/sharp), great. If not, and if you want to use it with Formoj, first [install the package](https://github.com/code16/sharp#installation).

Then we need to configure Formoj in `config/sharp.php`:

```php
return [

    [...]

    "entities" => [
        "form" => [
            "list" => \Code16\Formoj\Sharp\FormojFormSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojFormSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFormSharpValidator::class,
        ],
        "field" => [
            "list" => \Code16\Formoj\Sharp\FormojFieldSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojFieldSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFieldSharpValidator::class,
        ],
        "answer" => [
            "list" => \Code16\Formoj\Sharp\FormojAnswerSharpEntityList::class,
            "policy" => \Code16\Formoj\Sharp\Policies\FormojAnswerSharpPolicy::class,
        ],
    ],

    "menu" => [
        [
            "label" => "Formoj",
            "entities" => [
                [
                    "entity" => "form",
                    "label" => "Forms",
                    "icon" => "fa-list-alt"
                ],
                [
                    "entity" => "field",
                    "label" => "Fields",
                    "icon" => "fa-square-o"
                ],
                [
                    "entity" => "answer",
                    "label" => "Answers",
                    "icon" => "fa-envelope-o"
                ],
            ]
        ]
    ],

    [...]
];
```

This will add a full Formoj administration in Sharp:

![](/docs/img/form.png)

![](/docs/img/fields.png)

![](/docs/img/answers.png)

### Without Sharp

Formoj does not provide any other admin tool for now, so in this case, well, you're free to do what you want :).

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
- `$fileName` is the export file name (export directory ans disk are configurable in `config/formoj.php`)
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
