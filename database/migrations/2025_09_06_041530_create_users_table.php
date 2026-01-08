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
    Schema::create('users', function (Blueprint $table) {
        $table->id('user_id');
        $table->string('username', 100)->unique()->index();
        $table->string('password');
        $table->enum('role', ['SuperAdmin','Admin','Coach','Staff','Athlete']);
        $table->integer('removed')->default(0)->index();
        $table->timestamps();
    });

    DB::table('users')->insert([
        'username'   => 'admin.athletix.ph@ctu.com',
        'password'   => Hash::make('Admin@Athletix'),
        'role'       => 'SuperAdmin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
