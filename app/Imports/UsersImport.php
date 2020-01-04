<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
class UsersImport implements ToModel
{
    use Importable;
/**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new User([
            'eid' => $row[0],
            'name'     => $row[1],
            'occupation' => $row[2],
            'working_department' => $row[3],
            'status' => $row[4],
            'database_role'    => $row[2], 
            'password' => Hash::make($row[6]),
            'email'    => $row[0].'@ksrce.com' 
            
        ]);
    }
}

  
