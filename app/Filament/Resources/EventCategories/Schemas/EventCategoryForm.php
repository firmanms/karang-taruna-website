<?php

namespace App\Filament\Resources\EventCategories\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Kategori Agenda')
                    ->description('Kelola data kategori agenda dan kegiatan')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', $state ? Str::slug($state).'-'.strtolower(Str::random(5)) : '')),
                        Hidden::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),
            ]);
    }
}
