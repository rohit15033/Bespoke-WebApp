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
                            {name : The name of the user}
                            {email : The email of the user}
                            {password : The password for the user}
                            {--admin : Whether the user should be an admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with optional admin privileges';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $isAdmin = $this->option('admin');

        // Validate inputs
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Create user data
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ];

        // Add admin role if specified (assuming you have a role field)
        if ($isAdmin) {
            $userData['role'] = 'admin';
            $this->info('Creating admin user...');
        } else {
            $this->info('Creating regular user...');
        }

        // Create the user
        try {
            $user = User::create($userData);
            
            $this->info('User created successfully!');
            $this->info("ID: {$user->id}");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Role: " . ($isAdmin ? 'admin' : 'user'));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to create user: ' . $e->getMessage());
            return 1;
        }
    }
}