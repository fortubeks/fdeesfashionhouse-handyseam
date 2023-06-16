<?php

namespace App\Policies;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppSettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if($user->user_type == "admin"){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppSetting  $appSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AppSetting $appSetting)
    {
        if($user->user_type == "admin"){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->user_type == "admin"){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppSetting  $appSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AppSetting $appSetting)
    {
        if($user->user_type == "admin"){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppSetting  $appSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AppSetting $appSetting)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppSetting  $appSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AppSetting $appSetting)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppSetting  $appSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AppSetting $appSetting)
    {
        //
    }
}
