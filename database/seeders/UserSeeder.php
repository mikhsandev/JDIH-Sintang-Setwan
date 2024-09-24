<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default user
        User::factory()
            ->create([
                'name'  => 'Administrator',
                'username'  => 'admin',
                'email' => 'admin@jdih.sintang.go.id',
                'role'  => UserRole::ADMIN,
                'password' => bcrypt('AdminJdihSintang@2024'),
            ]);

        User::factory()
            ->create([
                'name'  => 'Editor',
                'username'  => 'editor',
                'email' => 'editor@jdih.sintang.go.id',
                'role'  => UserRole::EDITOR,
                'password' => bcrypt('EditorJdihSintang@2024'),
            ]);

        User::factory()
            ->create([
                'name'  => 'Author',
                'username'  => 'author',
                'email' => 'author@jdih.sintang.go.id',
                'role'  => UserRole::AUTHOR,
                'password' => bcrypt('AuthorJdihSintang@2024'),
            ]);
    }
}
