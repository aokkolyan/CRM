<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Category $category)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Category $category)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission,$category);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Category $category)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission,$category);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Category $category)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Category $category)
    {
        $moduleName = PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission = __FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user, $moduleName, $permission);
    }
}
