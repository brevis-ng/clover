<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\Roles;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Cache;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
