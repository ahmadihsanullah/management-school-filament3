<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teachers_id', 'id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withPivot('description');
    }
}
