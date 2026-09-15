<?php

namespace TomatoPHP\FilamentDocs\Tests;

use Illuminate\Support\Facades\DB;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Models\Document;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

const MALICIOUS_BODY = '<script>alert(1)</script>'
    . '<img src="x" onerror="alert(1)">'
    . '<a href="javascript:alert(1)">click</a>'
    . '<p style="color: red">Styled paragraph</p>'
    . '<ul><li>Bullet item</li></ul>'
    . '<table><tbody><tr><th>Item</th><td>Value</td></tr></tbody></table>';

function assertSanitized(string $html): void
{
    expect($html)
        ->not->toContain('alert(1)')
        ->not->toContain('onerror')
        ->not->toContain('javascript:')
        ->toContain('<table>')
        ->toContain('<th>Item</th>')
        ->toContain('<ul><li>Bullet item</li></ul>')
        ->toContain('style="color: red"');
}

beforeEach(function () {
    actingAs(User::factory()->create());

    $templateId = DB::table('document_templates')->insertGetId([
        'name' => 'Unsafe', 'body' => MALICIOUS_BODY, 'is_active' => true,
        'created_at' => now(), 'updated_at' => now(),
    ]);

    // Inserted without Eloquent so the stored HTML is still unsafe and the render path is what is tested.
    $this->documentId = DB::table('documents')->insertGetId([
        'document_template_id' => $templateId, 'body' => MALICIOUS_BODY, 'ref' => 'XSS',
        'created_at' => now(), 'updated_at' => now(),
    ]);
});

it('sanitizes the document body on the print page', function () {
    $response = get(DocumentResource::getUrl('print', ['record' => $this->documentId]))->assertSuccessful();

    assertSanitized((string) $response->getContent());
});

it('sanitizes the document body in the view modal content', function () {
    // The "view" table action renders exactly this view as its modal content.
    $html = view('filament-docs::print', ['record' => Document::query()->findOrFail($this->documentId)])->render();

    assertSanitized($html);
});

it('sanitizes document and template bodies when they are saved', function () {
    $template = DocumentTemplate::query()->create(['name' => 'Saved', 'body' => MALICIOUS_BODY . '<p>$USER_ID</p>']);
    $document = Document::query()->create(['document_template_id' => $template->id, 'body' => MALICIOUS_BODY]);

    assertSanitized($template->refresh()->body);
    assertSanitized($document->refresh()->body);

    expect($template->body)->toContain('$USER_ID');
});
