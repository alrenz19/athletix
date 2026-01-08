<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'hei_code',
        'hei_name',
        'template_improvements',
        'additional_data',
        'difficult_data',
        'easy_data',
        'additional_comments',
        'respondent_name',
        'respondent_email',
        'respondent_position',
        'academic_year',
        'submitted_at'
    ];

     // Set default values
    protected $attributes = [
        'user_id' => null, // or set a default user ID if needed
    ];

    // Automatically set user_id when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
            $model->hei_name = $model->hei_name ?? 'Cebu Technological University - Main Campus';
            $model->academic_year = $model->academic_year ?? '2022-2023';
            $model->submitted_at = $model->submitted_at ?? now();
        });
    }
}