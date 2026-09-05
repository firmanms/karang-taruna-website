<?php

namespace App\Filament\Resources\ProgramDivisions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProgramDivisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Divisi / Bidang Kerja')
                    ->description('Kelola identitas divisi dan nomor urut tampilan')
                    ->schema([
                        TextInput::make('division_name')
                            ->label('Nama Divisi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('order_index')
                            ->label('Nomor Urut')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Textarea::make('description')
                            ->label('Deskripsi / Tugas Pokok')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
