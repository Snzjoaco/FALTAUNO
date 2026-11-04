<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakePlatformAdmin extends Command
{
    protected $signature = 'app:make-admin {email : Email de una cuenta existente}';

    protected $description = 'Asigna el rol de administrador de plataforma a una cuenta existente';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('No existe una cuenta con ese email. Registrala primero y vuelve a ejecutar el comando.');

            return self::FAILURE;
        }

        $user->role = User::ROLE_PLATFORM_ADMIN;
        $user->is_suspended = false;
        $user->save();

        $this->info("Se asignó el rol de administrador de plataforma a {$user->email}.");

        return self::SUCCESS;
    }
}
