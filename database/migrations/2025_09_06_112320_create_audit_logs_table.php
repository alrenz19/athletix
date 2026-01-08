<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('action');          // e.g., 'create', 'update', 'delete', 'login'
            $table->string('module');          // e.g., 'Athlete', 'Team', 'Event', 'Settings'
            $table->text('description')->nullable(); // optional details
            $table->string('ip_address', 45)->nullable(); // store IP
            $table->timestamps();             // created_at, updated_at

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
