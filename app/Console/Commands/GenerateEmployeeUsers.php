<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class GenerateEmployeeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-from-employees
                            {--password=default123 : Default password for new accounts}
                            {--role=Employee : Role to assign to new users}
                            {--dry-run : Preview without creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate user accounts for all employees who do not have one yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultPassword = $this->option('password');
        $roleName = $this->option('role');
        $dryRun = $this->option('dry-run');

        // Ensure the role exists
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' does not exist. Please create it first.");
            return 1;
        }

        // Get active employees who don't have a user account
        $employees = Employee::where('status', 1)
            ->whereDoesntHave('user')
            ->get();

        if ($employees->isEmpty()) {
            $this->info('All active employees already have user accounts. Nothing to do.');
            return 0;
        }

        $this->info("Found {$employees->count()} employee(s) without user accounts.");

        if ($dryRun) {
            $this->warn('--- DRY RUN MODE (no changes will be made) ---');
        }

        $table = [];
        $created = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            // Skip if email is empty
            if (empty($employee->email)) {
                $table[] = [$employee->emply_id, $employee->name, 'SKIPPED', 'No email'];
                $skipped++;
                continue;
            }

            // Check if email or employee_id already taken in users table
            $existingUser = User::where('email', $employee->email)
                ->orWhere('employee_id', $employee->emply_id)
                ->first();

            if ($existingUser) {
                $table[] = [$employee->emply_id, $employee->name, 'SKIPPED', 'Email/ID already in use'];
                $skipped++;
                continue;
            }

            // Generate a unique username from employee ID
            $username = Str::slug($employee->emply_id);

            // Ensure username uniqueness
            if (User::where('username', $username)->exists()) {
                $username = $username . '-' . Str::random(4);
            }

            if (!$dryRun) {
                $user = User::create([
                    'name' => $employee->name,
                    'username' => $username,
                    'employee_id' => $employee->emply_id,
                    'email' => $employee->email,
                    'password' => bcrypt($defaultPassword),
                    'must_change_password' => true,
                ]);

                $user->assignRole($role);
            }

            $table[] = [$employee->emply_id, $employee->name, 'CREATED', $username];
            $created++;
        }

        $this->table(['Employee ID', 'Name', 'Status', 'Details'], $table);

        $this->newLine();
        $this->info("Created: {$created} | Skipped: {$skipped} | Total: " . ($created + $skipped));

        if (!$dryRun && $created > 0) {
            $this->warn("Default password: '{$defaultPassword}' — Users will be forced to change on first login.");
        }

        return 0;
    }
}
