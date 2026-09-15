<?php

namespace TomatoPHP\FilamentDocs\Tests;

use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Tests\Models\Document;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(User::factory()->create());

    $template = DocumentTemplate::factory()->create();

    $this->document = Document::factory()->withId($template->id)->create([
        'body' => '<h2>Contract terms</h2><ul><li>First bullet</li><li>Second bullet</li></ul><ol><li>Step one</li></ol><table><tr><th>Item</th><td>Value</td></tr></table>',
    ]);
});

it('renders the print page with the document body', function () {
    get(DocumentResource::getUrl('print', ['record' => $this->document]))
        ->assertSuccessful()
        ->assertSee('<h2>Contract terms</h2>', false)
        ->assertSee('<li>First bullet</li>', false)
        ->assertSee('<th>Item</th>', false);
});

it('styles the print page with the bundled stylesheet instead of the tailwind play cdn', function () {
    get(DocumentResource::getUrl('print', ['record' => $this->document]))
        ->assertSuccessful()
        ->assertSee('id="filament-docs-print-css"', false)
        ->assertSee('.fd-document ul { list-style: disc outside; }', false)
        ->assertSee('.fd-document ol { list-style: decimal outside; }', false)
        ->assertSee('.fd-document th, .fd-document td {', false)
        ->assertDontSee('cdn.tailwindcss.com', false);
});

it('adds the registered custom css, header and footer to the print page', function () {
    view()->addNamespace('docs-test', __DIR__ . '/../resources/views');

    FilamentDocs::css('docs-test::print-css');
    FilamentDocs::header('docs-test::print-header');
    FilamentDocs::footer('docs-test::print-footer');

    get(DocumentResource::getUrl('print', ['record' => $this->document]))
        ->assertSuccessful()
        ->assertSee('id="filament-docs-custom-css"', false)
        ->assertSee('.custom-print-rule', false)
        ->assertSee('Company Header', false)
        ->assertSee('Company Footer', false);
});
