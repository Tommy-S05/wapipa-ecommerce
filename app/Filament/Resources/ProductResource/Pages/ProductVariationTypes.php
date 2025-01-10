<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypeEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class ProductVariationTypes extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $navigationIcon = 'heroicon-c-numbered-list';
    public function getTitle(): string|Htmlable
    {
        return __('Variation Types');
    }
    public static function getNavigationLabel(): string
    {
        return __('Variation Types');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('variationTypes')
                    ->label(false)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required(),
                        Select::make('type')
                            ->label(__('Type'))
                            ->options(ProductVariationTypeEnum::labels())
                            ->live()
                            ->required(),

                        Repeater::make('options')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Name'))
                                    ->columnSpan(2)
                                    ->required(),
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->label(__('Images'))
                                    ->collection('images')
                                    ->disk('public')
                                    ->image()
                                    ->multiple()
                                    ->openable()
                                    ->panelLayout('grid')
                                    ->reorderable()
                                    ->appendFiles()
                                    ->preserveFilenames()
                                    //                    ->moveFiles()
                                    ->imageEditor()
                                    ->downloadable()
                                    ->columnSpan(3)
                                    ->visible(fn(Get $get): bool => $get('../../type') === ProductVariationTypeEnum::Image->value),
                            ])
                            ->relationship()
                            ->collapsible()
                            ->addActionLabel(__('Add Option'))
                            ->columns(2)
                            ->columnSpan(2),
                    ])
                    ->relationship()
                    ->collapsible()
                    ->defaultItems(1)
                    ->addActionLabel(__('Add Variation Type'))
                    ->columns(2)
                    ->columnSpan(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
