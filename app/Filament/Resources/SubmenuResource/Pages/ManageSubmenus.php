<?php

namespace App\Filament\Resources\SubmenuResource\Pages;

use App\Filament\Resources\SubmenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubmenus extends ManageRecords
{
    protected static string $resource = SubmenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
