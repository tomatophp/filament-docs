<?php

namespace TomatoPHP\FilamentDocs\Tests;

use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages;
use TomatoPHP\FilamentDocs\Models\DocumentTemplateVar;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can render the document template list page', function () {
    get(DocumentTemplateResource::getUrl())->assertSuccessful();
});

it('can list document templates', function () {
    $templates = DocumentTemplate::factory()->count(5)->create();

    livewire(Pages\ListDocumentTemplates::class)
        ->loadTable()
        ->assertCanSeeTableRecords($templates)
        ->assertCountTableRecords(5);
});

it('can render the document template create page', function () {
    get(DocumentTemplateResource::getUrl('create'))->assertSuccessful();
});

it('can render the document template edit page', function () {
    get(DocumentTemplateResource::getUrl('edit', [
        'record' => DocumentTemplate::factory()->create(),
    ]))->assertSuccessful();
});

it('can create a document template and registers its vars', function () {
    livewire(Pages\CreateDocumentTemplate::class)
        ->fillForm([
            'name' => 'Welcome letter',
            'body' => '<p>Hello $USER_ID, today is $DATE</p>',
            'icon' => 'heroicon-o-document-text',
            'color' => '#ff0000',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('document_templates', [
        'name' => 'Welcome letter',
        'is_active' => true,
    ]);

    $template = DocumentTemplate::query()->where('name', 'Welcome letter')->firstOrFail();

    expect($template->body)->toContain('$USER_ID')
        ->and(DocumentTemplateVar::query()->where('document_template_id', $template->id)->pluck('var')->all())
        ->toBe(['$USER_ID']);
});

it('validates the document template name and body', function () {
    livewire(Pages\CreateDocumentTemplate::class)
        ->fillForm([
            'name' => null,
            'body' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'body',
        ]);
});

it('can retrieve and save document template data', function () {
    $template = DocumentTemplate::factory()->create();

    livewire(Pages\EditDocumentTemplate::class, [
        'record' => $template->getRouteKey(),
    ])
        ->assertSchemaStateSet([
            'name' => $template->name,
        ])
        ->fillForm([
            'name' => 'Updated template',
            'body' => '<ul><li>First</li><li>Second</li></ul>',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($template->refresh())
        ->name->toBe('Updated template')
        ->body->toContain('<ul>', 'First', 'Second');
});

it('can delete a document template', function () {
    $template = DocumentTemplate::factory()->create();

    livewire(Pages\ListDocumentTemplates::class)
        ->callTableAction('delete', $template);

    assertModelMissing($template);
});
