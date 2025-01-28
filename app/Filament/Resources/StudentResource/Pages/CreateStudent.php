<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
    protected function handleRecordCreation(array $data): Model
    {

        $record = new ($this->getModel())($data);
        $record->save();
        // $role_id = $data['role'];

        $role = Role::find(4);
        $record->assignRole($role);
        return $record;
    }
}
