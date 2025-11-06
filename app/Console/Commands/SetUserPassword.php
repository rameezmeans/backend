<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SetUserPassword extends Command
{
    protected $signature = 'user:set-password {email} {password}';
    protected $description = 'Set the given password for every user record with the specified email.';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $users = User::where('email', $email)->get();

        if ($users->isEmpty()) {
            $this->error("No users found for {$email}");
            return 1;
        }

        $hashed = Hash::make($password);

        foreach ($users as $user) {
            $user->password = $hashed;
            $user->save();
            Log::info('Password updated via CLI', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'by'      => get_current_user(),
                'time'    => now()->toDateTimeString(),
            ]);
        }

        $this->info("Password updated for {$users->count()} user(s) with {$email}");
        return 0;
    }
}