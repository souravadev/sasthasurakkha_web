<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        //End users
        Schema::create('eusers', function (Blueprint $table) {
            $table->bigInteger('user_id')->primary();
            $table->uuid('guid')->unique()->default(DB::raw('uuid_generate_v4()'));
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 15)->unique();
            $table->string('profile_image')->nullable();
            $table->boolean('is_email_verified')->default(false);
            $table->boolean('is_phone_verified')->default(false);
            $table->string('remember_token');
            $table->boolean('is_cancel')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('eroles', function(Blueprint $table) {
            $table->bigIncrements('role_id')->primary();
            $table->string('name')->unique();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('euser_roles', function(Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->references('user_id')->on('eusers');
            $table->foreignId('role_id')->references('role_id')->on('eroles');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('otps', function(Blueprint $table) {
            $table->bigInteger('action_id')->primary();
            $table->foreignId('user_id')->references('user_id')->on('eusers');
            $table->string('email')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('otp', 6);
            $table->foreignId('purpose_id')->references('purpose_id')->on('action_purposes');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_used')->default(false);
            $table->timestamp('expiry_at');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('supereadmins', function(Blueprint $table) {
            $table->id()->primary();
            $table->string('email');
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });




        // Create Trigger Function
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_updated_at_column()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = NOW();
                RETURN NEW;
            END;
            $$ language \'plpgsql\';
        ');

        $tables = [
            'eusers', 
            'eroles', 
            'otps', 
            'supereadmins',
            'euser_roles',
         ];

        foreach ($tables as $table) {
            DB::unprepared("
                CREATE TRIGGER set_updated_at_{$table}
                BEFORE UPDATE ON {$table}
                FOR EACH ROW
                EXECUTE FUNCTION update_updated_at_column();
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'eusers', 
            'eroles',
            'otps',
            'supereadmins', 
            'action_purposes',
            'euser_roles',
        ];

        foreach($tables as $table) {
            // Drop Trigger & Function First
            DB::unprepared("DROP TRIGGER IF EXISTS set_updated_at ON {$table};");
            DB::unprepared("DROP FUNCTION IF EXISTS update_updated_at_column();");

            Schema::dropIfExists($table);
        }
    }
};
