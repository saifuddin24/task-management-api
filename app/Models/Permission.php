<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'guard_name',
    ];

    public static function available_permissions():Collection{
        return collect([
            "project.read", "project.create", "project.update", "project.delete",
            "task.read","task.create","task.update","task.delete",
            "task-activity.read","task-activity.create","task-activity.update","task-activity.delete","task-activity.finish",
            /****************************************************************************************/
            "user.read", "user.create","user.make-active", "user.update", "user.delete",
            "role.read","role.create","role.update","role.delete",
            "permission.read","permission.create","permission.update","permission.delete",
            "user-role.read","user-role.create","user-role.update","user-role.delete",
            "user-permission.read","user-permission.create","user-permission.update","user-permission.delete",
        ]);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permission');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
