# Changelog

## v5.0.0

- Support Filament v5 and Laravel 12 / 13 (PHP 8.2+), with tomatophp/filament-icons ^5.0.
- Template and document bodies use Filament's built-in `RichEditor` instead of `awcodes/filament-tiptap-editor` (existing HTML bodies keep working).
- The print page ships its own self-contained stylesheet (headings, bullet and numbered lists, tables, blockquotes, code) and no longer loads Tailwind from `cdn.tailwindcss.com`, so printed documents keep their formatting (#10).
- Actions use the unified `Filament\Actions\Action` API; `DocumentAction`, `PrintAction` (page, table and notification variants) and `DocumentRelationManager` keep their public API.
- Test suite for both resources (list, create, edit, round-trip), the print page, the actions, the relation manager and the install command.
