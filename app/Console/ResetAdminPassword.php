<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {password?}';
    protected $description = 'Reset admin password with proper Bcrypt hashing';

    public function handle()
    {
        // Find admin user
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->error('Admin user not found!');
            return 1;
        }

        // Get password from argument or ask for it
        $password = $this->argument('password') ?: $this->secret('Enter new password for admin:');

        if (empty($password)) {
            $this->error('Password cannot be empty!');
            return 1;
        }

        // Update password with proper Bcrypt hashing
        $admin->password = Hash::make($password);
        $admin->save();

        $this->info('Admin password has been reset successfully!');
        return 0;
    }
}