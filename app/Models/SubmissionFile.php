<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    protected $table = 'submission_files';

    protected $fillable = [
        'submission_id',
        'file_path',
        'file_name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }
}
