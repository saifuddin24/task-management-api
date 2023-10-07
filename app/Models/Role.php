<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected const super_admin_ids = [1];

    protected const permission_levels = [
        1 => [1],
        2 => [2,3],
        3 => [4,5,6,7,8],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission' );
    }

    public static function isSuperAdmin( $id ): bool
    {
        return in_array( $id, self::super_admin_ids );
    }

    public function getIsSuperAdminAttribute( ):bool{
        return self::isSuperAdmin( $this->id );
    }

    public function getLevelAttribute(){
        foreach (self::permission_levels as $level  => $ids ) {
            if( in_array($this->id, $ids)) {
                return $level;
            }
        }
    }

    public function scopeLevel($query, $value){
        $levels = self::permission_levels[$value];
        if( isset($levels ) ){
            $query->whereIn( 'id', $levels );
        }
    }
}
