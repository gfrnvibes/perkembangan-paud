<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\Register;

class TeacherRegister extends Register
{
    // protected string $view = 'filament.pages.auth.teacher-register';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

        protected function handleRegistration(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => 'active',
        ]);

        $user->assignRole('guru');

        return $user;
    }
}
