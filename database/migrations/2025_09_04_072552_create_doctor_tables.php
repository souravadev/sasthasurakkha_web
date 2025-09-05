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
        Schema::create('doctors', function(Blueprint $table) {
            $table->bigIncrements('doctor_id')->primary();
            $table->foreignId('user_id')->references('user_id')->on('users');
            $table->string('work_email')->unique()->nullable();
            $table->string('work_phone', 15)->unique();
            $table->string('designation');
            $table->date('work_experience');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('specializations', function(Blueprint $table) {
            $table->bigIncrements('specialization_id')->primary();
            $table->string('name')->unique();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('doctor_specializations', function(Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors');
            $table->foreignId('specialization_id')->references('specialization_id')->on('specializations');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('clinics', function(Blueprint $table) {
            $table->bigIncrements('clinic_id')->primary();
            $table->foreignId('ward_id')->references('ward_id')->on('wards');
            $table->string('name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->text('address');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('doctor_clinics', function(Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors');
            $table->foreignId('clinic_id')->references('clinic_id')->on('clinics');
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
            'doctors',
            'specializations',
            'doctor_specializations',
            'clinics',
            'doctor_clinics'
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
            'doctors',
            'specializations',
            'doctor_specializations',
            'clinics',
            'doctor_clinics'
        ];

        foreach($tables as $table) {
            // Drop Trigger & Function First
            DB::unprepared("DROP TRIGGER IF EXISTS set_updated_at ON {$table};");
            DB::unprepared("DROP FUNCTION IF EXISTS update_updated_at_column();");

            Schema::dropIfExists($table);
        }
    }
};
