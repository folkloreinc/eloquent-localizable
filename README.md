# Eloquent Localizable
Simple Laravel 4 and 5 package to add localization capabilities to Eloquent Models

## Installation

#### Dependencies:

* [Laravel 5.x](https://github.com/laravel/laravel)

#### Installation:

**1-** Require the package with Composer

```bash
$ composer require folklore/eloquent-localizable
```

## Usage

#### Configuration
This package use a Trait to add localization capabilities to your Eloquent modal. Basically it will add a `locales` relation to your model and provide a sync method for easy saving.

For example, if you have a `Page` model and you would like to add localized `title`, `description`. You first add the trait to your `Page` class.

```php

use Folklore\EloquentLocalizable\LocalizableTrait;

class Page extends Eloquent {
    
    use LocalizableTrait;
    
}
```

By default the trait will look for a `[MODEL_NAME]Locale` model. So in this example, it will looks for a `PageLocale`. Just create a `PageLocale` class next to your `Page` model.

```php

use Folklore\EloquentLocalizable\LocaleModel;

class PageLocale extends LocaleModel {
    
    protected $table = 'pages_locales';
    
}
```

You can change the locale model class by overiding the `getLocaleModelClass` method.

```php

use Folklore\EloquentLocalizable\LocalizableTrait;

class Page extends Eloquent {
    
    use LocalizableTrait;
    
    protected function getLocaleModelClass()
    {
        return 'App\Models\CustomLocaleModel';
    }
    
}
```

You also need to create the table for you localization. Following the example, we will create a migration for a `pages_locales` table.

```php
Schema::create('pages_locales', function(Blueprint $table)
{
	$table->increments('id');
	$table->integer('page_id')->unsigned();
	$table->string('locale',2);
	$table->string('title');
	$table->string('description');
	$table->timestamps();
	
	$table->index('page_id');
	$table->index('locale');
});
```

#### Getting locales
You can now use the locales relation on your `Page` model.

Getting a page with all locales
```php
$page = Page::with('locales')->first();

//Getting the title for a specific locale
echo $page->locales->fr->title;

//Looping through all locales
foreach($page->locales as $locale)
{
    echo $locale->locale.': '.$locale->title;
}
```

Getting a page with a specific locale
```php
$page = Page::withLocale('fr')->first();

//Getting a the title
echo $page->locale->fr->title;
```

If you want to always include the locales when fetching a page.
```php

use Folklore\EloquentLocalizable\LocalizableTrait;

class Page extends Eloquent {
    
    use LocalizableTrait;
    
    protected $with = ['locales'];
    
}
```

#### Saving locales
You can use the `syncLocales` method to save your locales.

```php
$locales = array(
    'en' => array(
        'title' => 'A page',
        'description' => 'This is the description of this page'
    ),
    'fr' => array(
        'title' => 'Une page',
        'description' => 'Ceci est la description de cette page'
    )
);

//or

$locales = array(
    array(
        'locale' => 'en',
        'title' => 'A page',
        'description' => 'This is the description of this page'
    ),
    array(
        'locale' => 'fr',
        'title' => 'Une page',
        'description' => 'Ceci est la description de cette page'
    )
);

$page = new Page();
$page->save(); //We need an id for this page, so we save before.

$page->syncLocales($locales);
```
