<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SetUserPassword extends Command
{
    protected $signature = 'user:set-password {email} {password}';
    protected $description = 'Set a user password by email (admin only, CLI).';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        $user->password = Hash::make($password);
        $user->save();

        Log::info('Admin set user password via CLI', [
            'user_id' => $user->id,
            'by' => get_current_user(),
            'time' => now()->toDateTimeString(),
        ]);

        $this->info("Password set for {$email}");
        return 0;
    }
}