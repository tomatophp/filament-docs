![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-docs/master/arts/3x1io-tomato-docs.jpg)

# Filament Documents Editor

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
        ->isScopedToTenant() // if you want to use this plugin as a tenant
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
