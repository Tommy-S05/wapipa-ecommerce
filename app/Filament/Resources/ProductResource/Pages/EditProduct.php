<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('Edit :name', ['name' => __($this->getRecordTitle())]);
//        return __('filament-panels::resources/pages/edit-record.title', [
//            'label' => __($this->getRecordTitle()),
//        ]);
    }
    public static function getNavigationLabel(): string
    {
//        $title = str(class_basename(static::class))->kebab()->replace('-', ' ')->title()->value();
        return __('Edit Record');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
