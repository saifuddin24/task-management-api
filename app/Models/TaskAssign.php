<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskAssign extends Model
{
    use HasFactory;

    protected $table = 'task_user';

    public function activities():HasMany {
        return $this->hasMany(TaskActivity::class, 'task_user_id','id');
    }

    public function employee():BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }
}

