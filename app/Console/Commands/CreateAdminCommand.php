<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email? : البريد الإلكتروني للمسؤول} {--name=Admin : اسم المسؤول} {--password= : كلمة المرور}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إنشاء حساب مسؤول جديد للوحة التحكم';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?? $this->ask('أدخل البريد الإلكتروني للمسؤول', 'admin@example.com');
        $name = $this->option('name') ?? 'Admin';
        $password = $this->option('password') ?? $this->secret('أدخل كلمة المرور');

        if (empty($password)) {
            $password = 'admin123456';
            $this->info("تم تعيين كلمة مرور افتراضية: {$password}");
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        $this->info("✓ تم إنشاء/تحديث حساب المسؤول بنجاح: {$user->email}");

        return self::SUCCESS;
    }
}
