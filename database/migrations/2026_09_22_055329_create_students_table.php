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
        
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('dob');
            $table->string('gender');
            $table->text('address');
            $table->string('city');
            $table->string('pincode', 6);

            $table->string('qualification');
            $table->string('college');
            $table->year('graduation_year');
            $table->text('skills')->nullable();

            $table->string('profile_image');
            $table->string('resume');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
