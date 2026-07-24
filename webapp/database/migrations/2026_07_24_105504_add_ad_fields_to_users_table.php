<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id'); // sAMAccountName AD
            $table->string('guid')->nullable()->unique()->after('username'); // objectGUID AD
            $table->enum('auth_source', ['ldap', 'local'])->default('ldap')->after('password');
            $table->boolean('is_active')->default(true)->after('auth_source');
            $table->timestamp('last_login_at')->nullable()->after('is_active');

            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'guid', 'auth_source', 'is_active', 'last_login_at']);
        });
    }
};
