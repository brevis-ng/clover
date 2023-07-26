<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\Roles;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $this->data["role"] == Roles::ADMIN
            ? Cache::forever("administrator", User::admin()->get())
            : Cache::forever("assistant", User::assistant()->get());
    }
}
