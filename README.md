![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/3x1io-tomato-docs.jpg)

# Filament Documents Editor

[![Dependabot Updates](https://github.com/tomatophp/filament-docs/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/toma
[![PHP Code Styling](https://github.com/tomatophp/filament-docs/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-docs/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-docs/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-docs/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-docs/version.svg)](https://packagist.org/packages/tomatophp/filament-docs)
[![License](https://poser.pugx.org/tomatophp/filament-docs/license.svg)](https://packagist.org/packages/tomatophp/filament-docs)
[![Downloads](https://poser.pugx.org/tomatophp/filament-docs/d/total.svg)](https://packagist.org/packages/tomatophp/filament-docs)

Manage your documents and contracts all in one place with template builder

## Features

- [x] Generate Documents From Template
- [x] Build Template using Tiptop Editor
- [x] Add Custom Vars By Facade
- [x] Generate Documents Action
- [x] Documents Filter By Template
- [x] Print Document or Export as PDF
- [x] Documents Relation Manager
- [x] Custom Print Header & Footer
- [x] Custom Print CSS

## Screenshots

![Documents](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/documents.png)
![Create Document](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/create-document.png)
![Documents Filters](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/documents-filters.png)
![Print Document](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/print-document.png)
![Templates](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/templates.png)
![Create Template](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/create-template.png)
![Edit Template](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/edit-template.png)
![Edit Template Vars](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/edit-template-vars.png)
![Edit Template Icons](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/edit-template-icon.png)
![Document Action](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/document-action.png)
![Document Relation Manager](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/document-relation.png)
![Generate Document](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/generate-document.png)
![Generate Document Notification](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/generate-notification.png)

## Installation

```bash
composer require tomatophp/filament-docs
```
after install your package please run this command

```bash
php artisan filament-docs:install
```

if you are not using this package as a plugin please register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(
    \TomatoPHP\FilamentDocs\FilamentDocsPlugin::make()
)
```

## Using

you can add the action to any table like this

```php
use TomatoPHP\FilamentDocs\Filament\Actions\DocumentAction;

DocumentAction::make()
    ->vars(fn($record) => [
        DocsVar::make('$ACCOUNT_NAME')
            ->value($record->name),
        DocsVar::make('$ACCOUNT_EMAIL')
            ->value($record->email),
        DocsVar::make('$ACCOUNT_PHONE')
            ->value($record->phone)
    ])
```

and then you can use `$ACCOUNT_NAME` in your template

if you like to add a Global Var you can use Facade class like this

```php
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;

public function boot()
{
    FilamentDocs::register([
        DocsVar::make('$POST_TITLE')
            ->label('Post Title')
            ->model(Post::class)
            ->column('title'),
        DocsVar::make('$POST_TYPE')
            ->label('Post Type')
            ->model(Post::class)
            ->column('type'),
        DocsVar::make('$SELECTED_TIME')
            ->label('SELECTED TIME')
            ->value(fn () => Carbon::now()->subDays(10)->translatedFormat('D-M-Y')),
    ]);
}
```

as you can see you can use data from selected table or from a static function


## Add Fixed Header & Footer to Document Print


if you like to add a fixed header and footer to your document print you can use this method on your `AppServiceProvider.php` file

```php
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;

public function boot() {
    FilamentDocs::header('filament.header');
    FilamentDocs::footer('filament.footer');
} 
```

## Custom CSS on Document Print

if you like to add a custom css to your document print you can use this method on your `AppServiceProvider.php` file

```php
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;

public function boot() {
    FilamentDocs::css('filament.css');
} 
```

## Allow Tenants 

to allow tenants just use this method

```php
->plugin(
    \TomatoPHP\FilamentDocs\FilamentDocsPlugin::make()
        ->isScopedToTenant()
)
```

and add this migration 


```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
        });
        
        Schema::table('document_templates', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
        
        Schema::table('document_templates', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
    }
};

```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-docs-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-docs-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-docs-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-docs-migrations"
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
