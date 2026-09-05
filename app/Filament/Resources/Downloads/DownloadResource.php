<?php

namespace App\Filament\Resources\Downloads;

use App\Domain\Content\Models\Download;
use App\Filament\Resources\Downloads\Pages\CreateDownload;
use App\Filament\Resources\Downloads\Pages\EditDownload;
use App\Filament\Resources\Downloads\Pages\ListDownloads;
use App\Filament\Resources\Downloads\Pages\ViewDownload;
use App\Filament\Resources\Downloads\Schemas\DownloadForm;
use App\Filament\Resources\Downloads\Schemas\DownloadInfolist;
use App\Filament\Resources\Downloads\Tables\DownloadsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Pusat Unduhan';

    protected static ?string $modelLabel = 'Dokumen Unduhan';

    protected static ?string $pluralModelLabel = 'Pusat Unduhan Dokumen';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    public static function form(Schema $schema): Schema
    {
        return DownloadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DownloadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DownloadsTable::configure($table);
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
            'index' => ListDownloads::route('/'),
            'create' => CreateDownload::route('/create'),
            'view' => ViewDownload::route('/{record}'),
            'edit' => EditDownload::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->forUser();
    }
}
