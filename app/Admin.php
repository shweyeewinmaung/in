<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard='admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id','agent_id','is_super'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function role()
    {
        return $this->belongsTo('App\Role','role_id');
    }

    public function agent()
    {
        return $this->belongsTo('App\Agent','agent_id');
    }
    
    
    public function isSuper()
    {
        return $this->is_super;
    }

    public function hasAccess(array $permissions) : bool
    {
        // check if the permission is available in any role
        foreach ($this->role as $role) {

            if($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    public function inRole(string $roleSlug)
    {
        return $this->role()->where('slug', $roleSlug)->count() == 1;
    }

    public function hasPermission($permission){
        // dd($permission);
        if($this->role() != null){
            $user_permissions = $this->role()->first()->permissions;
            if(array_key_exists($permission,$user_permissions)){
                if($user_permissions[$permission]){
                   // dd($user_permissions[$permission]);
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }
        return false;
    }

}
