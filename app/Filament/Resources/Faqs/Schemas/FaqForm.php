<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tanya Jawab (FAQ)')
                    ->schema([
                        Textarea::make('question')
                            ->label('Pertanyaan (Question)')
                            ->required()
                            ->rows(2)
                            ->placeholder('Tuliskan pertanyaan umum masyarakat...'),
                        RichEditor::make('answer')
                            ->label('Jawaban Resmi (Answer)')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(3)->schema([
                            Select::make('category')
                                ->label('Kategori FAQ')
                                ->options([
                                    'Umum' => 'Umum',
                                    'Keanggotaan' => 'Keanggotaan Karang Taruna',
                                    'Program & Kegiatan' => 'Program & Kegiatan',
                                    'PPKS & Bansos' => 'PPKS & Bansos',
                                    'Legalitas & SK' => 'Legalitas & SK',
                                ])
                                ->default('Umum')
                                ->required(),
                            TextInput::make('order_index')
                                ->label('Urutan Tampilan')
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }
}
