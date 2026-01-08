<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('institutional_information', function (Blueprint $table) {
            $table->id();
            
            // User and HEI ownership
            $table->unsignedBigInteger('user_id');
            $table->string('hei_code')->nullable();
            $table->string('hei_name');
            
            // A1-HEI Information
            $table->string('hei_campus')->nullable();
            $table->text('hei_address');
            $table->string('hei_president');
            
            // Sports Director Information
            $table->string('sports_director_name');
            $table->string('sports_director_email');
            $table->string('sports_director_alt_email')->nullable();
            $table->string('sports_director_mobile');
            
            // RA 11180 Contact Person
            $table->string('contact_person_name');
            $table->string('contact_person_email');
            $table->string('contact_person_alt_email')->nullable();
            $table->string('contact_person_mobile');
            
            // A2-WELL Intramurals Activities
            $table->enum('departmental_intramurals', ['Yes', 'No'])->default('No');
            $table->enum('interdepartmental_intramurals', ['Yes', 'No'])->default('No');
            $table->enum('intercampus_intramurals', ['Yes', 'No'])->default('No');
            
            // A3-FACILITY Sports Facilities
            $table->json('facilities')->nullable();
            $table->text('other_facilities')->nullable();
            
            // Academic Year
            $table->string('academic_year')->default('2022-2023');
            
            // Ownership and timestamps
            $table->timestamps();
            
            // Indexes
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('hei_code');
            $table->index('hei_name');
            $table->index('academic_year');
            
            // Unique constraint - one record per HEI per academic year
            $table->unique(['hei_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutional_information');
    }
};