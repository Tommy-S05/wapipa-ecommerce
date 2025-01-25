<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

    public function getHeading(): string|Htmlable
    {
        return __('Create :name', ['name' => __(parent::getResource()::getTitleCaseModelLabel())]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
