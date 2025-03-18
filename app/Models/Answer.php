<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Answer extends Model
{
    use HasTranslations;

    protected $table = 'answers';

    public $translatable = ['answer_text'];

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
        'score',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'answer_id');
    }
}
