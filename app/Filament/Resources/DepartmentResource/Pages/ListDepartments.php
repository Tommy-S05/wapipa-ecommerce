<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('Departments');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('Create :name', ['name' => __(parent::getResource()::getTitleCaseModelLabel())])),
        ];
    }
}
