<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class StudentsImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = new User([
            'first_name'  => $row['first_name'],
            'last_name'   => $row['last_name'],
            'email' => $row['email'],
            'phone'    => $row['phone'],
            'password' => bcrypt('password'),
        ]);
        $role = Role::find(4);
        $user->assignRole($role);
        return $user;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
