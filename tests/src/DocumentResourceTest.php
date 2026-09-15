<?php

namespace TomatoPHP\FilamentDocs\Tests;

use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;
use TomatoPHP\FilamentDocs\Models\DocumentTemplateVar;
use TomatoPHP\FilamentDocs\Tests\Models\Document;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs($this->user = User::factory()->create());
});

it('can render document resource', function () {
    get(DocumentResource::getUrl())->assertSuccessful();
});

it('can list documents', function () {
    Document::query()->delete();
    $template = DocumentTemplate::factory()->create();
    $documents = Document::factory()->count(10)->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->loadTable()
        ->assertCanSeeTableRecords($documents)
        ->assertCountTableRecords(10);
});

it('can render document type/for/key column in table', function () {
    $template = DocumentTemplate::factory()->create();
    Document::factory()->count(10)->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->loadTable()
        ->assertCanRenderTableColumn('id')
        ->assertCanRenderTableColumn('ref')
        ->assertCanRenderTableColumn('is_send')
        ->assertCanRenderTableColumn('documentTemplate.name');
});

it('can filter documents by template', function () {
    $template = DocumentTemplate::factory()->create(['is_active' => true]);
    $otherTemplate = DocumentTemplate::factory()->create(['is_active' => true]);
    $documents = Document::factory()->count(2)->withId($template->id)->create();
    $otherDocuments = Document::factory()->count(2)->withId($otherTemplate->id)->create();

    livewire(Pages\ListDocuments::class)
        ->filterTable('document_template_id', $template->id)
        ->assertCanSeeTableRecords($documents)
        ->assertCanNotSeeTableRecords($otherDocuments);
});

it('can render document list page', function () {
    livewire(Pages\ListDocuments::class)->assertSuccessful();
});

it('can render the view document table action', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->mountTableAction('view', $document)
        ->assertSuccessful();
});

it('can render document create page', function () {
    get(DocumentResource::getUrl('create'))->assertSuccessful();

    livewire(Pages\CreateDocument::class)->assertSuccessful();
});

it('can create new document', function () {
    $template = DocumentTemplate::factory()->create();
    $newData = Document::factory()->withId($template->id)->make();

    livewire(Pages\CreateDocument::class)
        ->fillForm([
            'document_template_id' => $template->id,
            'body' => [],
            'ref' => $newData->ref,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Document::class, [
        'body' => $template->body,
        'ref' => $newData->ref,
    ]);
});

it('creates a document from a template and replaces its vars', function () {
    // A fixed name: the saved body is sanitized, which HTML-encodes characters such as the apostrophe in "O'Connor".
    $this->user->update(['name' => 'Jane Doe']);

    $template = DocumentTemplate::factory()->create([
        'body' => '<p>Dear $USER_ID</p><p>Ref $UUID</p>',
    ]);

    DocumentTemplateVar::query()->create([
        'document_template_id' => $template->id,
        'var' => '$USER_ID',
        'model' => User::class,
        'value' => 'name',
    ]);

    livewire(Pages\CreateDocument::class)
        ->fillForm([
            'document_template_id' => $template->id,
        ])
        ->assertSchemaStateSet(fn (array $state): array => [
            'body' => [
                array_key_first($state['body']) => [
                    'var' => '$USER_ID',
                    'label' => 'User ID',
                    'key' => 'name',
                    'value' => '',
                    'model' => User::class,
                ],
            ],
        ])
        ->fillForm(fn (array $state): array => [
            'body' => [
                array_key_first($state['body']) => [
                    ...$state['body'][array_key_first($state['body'])],
                    'value' => $this->user->id,
                ],
            ],
            'ref' => 'CONTRACT-1',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $document = Document::query()->where('ref', 'CONTRACT-1')->firstOrFail();

    expect($document->body)
        ->toContain('Dear ' . $this->user->name)
        ->not->toContain('$USER_ID')
        ->not->toContain('$UUID');
});

it('can validate document input', function () {
    livewire(Pages\CreateDocument::class)
        ->fillForm([
            'document_template_id' => null,
            'body' => null,
            'ref' => null,
            'is_send' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'document_template_id' => 'required',
        ]);
});

it('can render document edit page', function () {
    $template = DocumentTemplate::factory()->create();
    get(DocumentResource::getUrl('edit', [
        'record' => Document::factory()->withId($template->id)->create(),
    ]))->assertSuccessful();
});

it('can retrieve document data', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\EditDocument::class, [
        'record' => $document->getRouteKey(),
    ])
        ->assertSchemaStateSet([
            'document_template_id' => $document->document_template_id,
            'ref' => $document->ref,
        ]);
});

it('can validate edit document input', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\EditDocument::class, [
        'record' => $document->getRouteKey(),
    ])
        ->fillForm([
            'document_template_id' => null,
            'ref' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'document_template_id' => 'required',
        ]);
});

it('can save document data', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\EditDocument::class, [
        'record' => $document->getRouteKey(),
    ])
        ->fillForm([
            'document_template_id' => $template->id,
            'body' => '<h2>Edited</h2><ul><li>One</li></ul>',
            'ref' => 'EDITED-REF',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($document->refresh())
        ->document_template_id->toBe($template->id)
        ->ref->toBe('EDITED-REF')
        ->body->toContain('<h2>Edited</h2>', '<ul>', 'One');
});

it('can delete document', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->callTableAction('delete', $document)
        ->assertHasNoTableActionErrors();

    assertModelMissing($document);
});
