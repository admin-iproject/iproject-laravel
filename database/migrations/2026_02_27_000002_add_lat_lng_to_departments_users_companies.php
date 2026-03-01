<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Departments
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('address_line2');
            }
            if (!Schema::hasColumn('departments', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('zip');
            }
            if (!Schema::hasColumn('users', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });

        // Companies
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('zip');
            }
            if (!Schema::hasColumn('companies', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });
    }

    public function down(): void
    {
        foreach (['departments', 'users', 'companies'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn(['lat', 'lng']);
            });
        }
    }
};
