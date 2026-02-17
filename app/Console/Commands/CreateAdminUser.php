<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
                            {name? : The name of the user}
                            {email? : The email of the user}
                            {password? : The password for the user}
                            {--role= : The role for the user (master, owner, marketer, content_creator, admin)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with interactive role and permission selection';

    /**
     * Role-based permission presets
     */
    protected const ROLE_PERMISSIONS = [
        'master' => ['dashboard', 'appointments', 'events', 'attendance', 'orders', 'payments', 'inventory', 'users', 'history', 'leads', 'clients', 'marketing'],
        'owner' => ['dashboard', 'appointments', 'events', 'attendance', 'orders', 'payments', 'inventory', 'leads', 'clients', 'marketing'],
        'marketer' => ['events', 'inventory', 'appointments', 'attendance', 'payments', 'orders', 'leads', 'clients', 'marketing'],
        'content_creator' => ['attendance', 'appointments', 'events'],
        'admin' => ['dashboard', 'appointments', 'events', 'attendance', 'orders', 'payments', 'inventory', 'leads', 'clients'],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?: $this->ask('Full Name');
        $email = $this->argument('email') ?: $this->ask('Email Address');
        $password = $this->argument('password') ?: $this->secret('Password');
        $role = $this->option('role');

        if (!$role) {
            $role = $this->choice(
                'Select User Role',
                ['master', 'owner', 'marketer', 'content_creator', 'admin'],
                2 // Default to marketer
            );
        }

        // Validate inputs
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:master,owner,marketer,content_creator,admin',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $this->info("Creating {$role} user: {$name} <{$email}>...");

        // Create user data
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
            'permissions' => self::ROLE_PERMISSIONS[$role] ?? [],
            'email_verified_at' => now(),
        ];

        // Create the user
        try {
            $user = User::create($userData);
            
            $this->info('✅ User created successfully!');
            $this->info("ID: {$user->id}");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->role}");
            $this->info("Permissions: " . implode(', ', $user->permissions));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to create user: ' . $e->getMessage());
            return 1;
        }
    }
}