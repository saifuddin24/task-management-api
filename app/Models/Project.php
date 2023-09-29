<?php

namespace App\Models;

use App\Models\Traits\ValuesAssignable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Project extends Model
{
    use ValuesAssignable,SoftDeletes,HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slogan',
        'description',
        'deadline',
        'working_days_needed',
        'manager_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deadline' => 'date',
        'working_days_needed' => 'integer',
        'manager_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function assigned_teams( ) : BelongsToMany{
        return $this->belongsToMany(Team::class, 'team_project'); //TODO: Validate whether the relation is valid
    }

    public function assigned_persons() : BelongsToMany{
        return $this->belongsToMany( User::class, 'project_user' ); //TODO: Validate whether the relation is valid
    }


    public function assign_person( array $ids ){
        $this->assigned_persons()
            ->attach(
                $this->map_assign_ids(
                    $ids,'user_id'
                )
            );
    }

    public function assign_teams( array $ids ){
        $this->assigned_persons()
            ->attach(
                $this->map_assign_ids(
                    $ids,'team_id'
                )
            );
    }

}
