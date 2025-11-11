<?php

namespace App\Filament\Admin\Resources\DocumentCategories;

use App\Filament\Admin\Resources\DocumentCategories\Pages\CreateDocumentCategory;
use App\Filament\Admin\Resources\DocumentCategories\Pages\EditDocumentCategory;
use App\Filament\Admin\Resources\DocumentCategories\Pages\ListDocumentCategories;
use App\Filament\Admin\Resources\DocumentCategories\Pages\ViewDocumentCategory;
use App\Filament\Admin\Resources\DocumentCategories\Schemas\DocumentCategoryForm;
use App\Filament\Admin\Resources\DocumentCategories\Schemas\DocumentCategoryInfolist;
use App\Filament\Admin\Resources\DocumentCategories\Tables\DocumentCategoriesTable;
use App\Models\DocumentCategory;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|UnitEnum|null $navigationGroup = 'Dokumen';

    protected static ?string $navigationLabel = 'Kategori Dokumen';

    public static function form(Schema $schema): Schema
    {
        return DocumentCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentCategories::route('/'),
            'create' => CreateDocumentCategory::route('/create'),
            'view' => ViewDocumentCategory::route('/{record}'),
            'edit' => EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}
