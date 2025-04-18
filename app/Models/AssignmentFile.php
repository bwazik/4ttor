<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentFile extends Model
{
    protected $table = 'assignment_files';

    protected $fillable = [
        'assignment_id',
        'file_path',
        'file_name',
        'file_size',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
