<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('name');
            }
        });

        DB::table('users')
            ->whereNull('username')
            ->orderBy('id')
            ->get(['id', 'name', 'email'])
            ->each(function (object $user): void {
                $baseUsername = Str::of($user->email ?: $user->name)
                    ->before('@')
                    ->slug('_')
                    ->value();

                $baseUsername = filled($baseUsername) ? $baseUsername : "user_{$user->id}";
                $username = $baseUsername;
                $suffix = 1;

                while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                    $username = "{$baseUsername}_{$suffix}";
                    $suffix++;
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $username]);
            });

        if (! $hasUsernameColumn) {
            Schema::table('users', function (Blueprint $table): void {
                $table->unique('username');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
