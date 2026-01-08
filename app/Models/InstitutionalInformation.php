<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalInformation extends Model
{
    use HasFactory;

    protected $table = 'institutional_information';

    protected $fillable = [
        'user_id',
        'hei_code',
        'hei_name',
        'hei_campus',
        'hei_address',
        'hei_president',
        'sports_director_name',
        'sports_director_email',
        'sports_director_alt_email',
        'sports_director_mobile',
        'contact_person_name',
        'contact_person_email',
        'contact_person_alt_email',
        'contact_person_mobile',
        'departmental_intramurals',
        'interdepartmental_intramurals',
        'intercampus_intramurals',
        'facilities',
        'other_facilities',
        'academic_year'
    ];

    protected $casts = [
        'facilities' => 'array'
    ];
}