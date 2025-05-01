<?php

namespace Modules\YrpDashboard\Admin\Resources\UserResource\Pages;

use Modules\YrpDashboard\Admin\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
