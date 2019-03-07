# Formoj for Laravel

Formoj is a form generator package.

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


### Laravel module

Formoj works with Laravel 5.7+.

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

### Availability

### Notifications

### Sections

### Field types

## Embed a form

A given form can then be embedded anywhere with this DOM:

```php
<formoj form-id="1"></formoj>
```

## Work with answers

// job, cron, sharp command

## Styling the form
Formoj uses SASS/SCSS language for styles. You can import the style with sass import:
```scss
@import 'formoj/scss/themes/default';
```
The default theme is very basic and is meant to be customized by your own code.
Some variable are available and can be overidded, see [`formoj/scss/_variables.scss`](https://github.com/code16/formoj/blob/master/packages/formoj/scss/_variables.scss)

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
