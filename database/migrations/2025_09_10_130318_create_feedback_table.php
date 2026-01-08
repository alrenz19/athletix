<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            
            // User and HEI ownership
            $table->unsignedBigInteger('user_id');
            $table->string('hei_code')->nullable();
            $table->string('hei_name');
            
            // Feedback Questions
            $table->text('template_improvements')->nullable();
            $table->text('additional_data')->nullable();
            $table->text('difficult_data')->nullable();
            $table->text('easy_data')->nullable();
            $table->text('additional_comments')->nullable();
            
            // Respondent Information (Optional)
            $table->string('respondent_name')->nullable();
            $table->string('respondent_email')->nullable();
            $table->string('respondent_position')->nullable();
            
            // Metadata
            $table->string('academic_year')->default('2022-2023');
            $table->timestamp('submitted_at')->useCurrent();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('hei_code');
            $table->index('academic_year');
            $table->index('submitted_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
};