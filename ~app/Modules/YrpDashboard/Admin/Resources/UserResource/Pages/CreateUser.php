<?php

namespace Modules\YrpDashboard\Admin\Resources\UserResource\Pages;

use Modules\YrpDashboard\Admin\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
