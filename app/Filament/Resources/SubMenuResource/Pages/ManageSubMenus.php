<?php

namespace App\Filament\Resources\SubMenuResource\Pages;

use App\Filament\Resources\SubMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubMenus extends ManageRecords
{
    protected static string $resource = SubMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
