<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;

return new class extends Migration {
    public function up(): void
    {
        // Ensure the is_admin column exists (in case migration order differs)
        if (Schema::hasColumn('users', 'is_admin') === false) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('password');
            });
        }

        $email = env('ADMIN_EMAIL', 'admin@masud.dev');
        $name  = env('ADMIN_NAME', 'Masud');
        $pass  = env('ADMIN_PASSWORD', 'Masud@12'); // set in .env on cloud

        // Create or update the admin
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($pass),
                'is_admin' => true,
            ]
        );
    }

    public function down(): void
    {
        // No-op: we don't auto-delete the admin on rollback
    }
};
