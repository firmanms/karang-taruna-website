<?php

namespace App\Filament\Resources\PhotoGalleries;

use App\Domain\Content\Models\PhotoGallery;
use App\Filament\Resources\PhotoGalleries\Pages\CreatePhotoGallery;
use App\Filament\Resources\PhotoGalleries\Pages\EditPhotoGallery;
use App\Filament\Resources\PhotoGalleries\Pages\ListPhotoGalleries;
use App\Filament\Resources\PhotoGalleries\Pages\ViewPhotoGallery;
use App\Filament\Resources\PhotoGalleries\Schemas\PhotoGalleryForm;
use App\Filament\Resources\PhotoGalleries\Schemas\PhotoGalleryInfolist;
use App\Filament\Resources\PhotoGalleries\Tables\PhotoGalleriesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PhotoGalleryResource extends Resource
{
    protected static ?string $model = PhotoGallery::class;

    protected static string|UnitEnum|null $navigationGroup = 'Galeri & Media';

    protected static ?string $navigationLabel = 'Galeri Foto';

    protected static ?string $modelLabel = 'Galeri Foto';

    protected static ?string $pluralModelLabel = 'Galeri Foto Kegiatan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function form(Schema $schema): Schema
    {
        return PhotoGalleryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PhotoGalleryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhotoGalleriesTable::configure($table);
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
            'index' => ListPhotoGalleries::route('/'),
            'create' => CreatePhotoGallery::route('/create'),
            'view' => ViewPhotoGallery::route('/{record}'),
            'edit' => EditPhotoGallery::route('/{record}/edit'),
        ];
    }
}
