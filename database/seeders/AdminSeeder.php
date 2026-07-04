<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadminRole = Role::where('name', 'superadmin')->where('guard_name', 'admin')->first();

        $admins = [

            [
                'name' => 'Super Admin',
                'phone' => '01700000000',
                'email' => 'admin@admin.com',
                'password' => 'password',
                'status' => 'Active',
                'role' => $superadminRole,
            ],
        ];

        foreach ($admins as $data) {
            $user = Admin::where('email', $data['email'])->first();
            if (is_null($user)) {
                $user = new Admin();
                $user->name = $data['name'];
                $user->phone = $data['phone'];
                $user->email = $data['email'];
                $user->status = $data['status'];
                $user->password = Hash::make($data['password']);
                $user->save();

                if ($data['role']) {
                    $user->assignRole($data['role']);
                }
            }
        }
    }
}
