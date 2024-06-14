<?php

namespace App\Policies;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReferralPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function view(User $user, Referral $Referral=null)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function create(User $user)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function update(User $user, Referral $Referral=null)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission,$Referral);
    }

    public function delete(User $user, Referral $Referral=null)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission,$Referral);
    }

    public function restore(User $user, Referral $Referral)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function forceDelete(User $user, Referral $Referral)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function import(User $user)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function export(User $user)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function print(User $user)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function createForOthers(User $user)
    {
        $moduleName=PolicyHelper::getModuleName((new \ReflectionClass($this))->getShortName());
        $permission=__FUNCTION__;
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function viewAnyMyReferral(User $user)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function viewMyReferral(User $user, Referral $myReferral=null)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function createMyReferral(User $user)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function updateMyReferral(User $user, Referral $Referral=null)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission,$Referral);
    }

    public function deleteMyReferral(User $user, Referral $Referral=null)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission,$Referral);
    }

    public function restoreMyReferral(User $user, Referral $Referral)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function forceDeleteMyReferral(User $user, Referral $Referral)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function importMyReferral(User $user)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function exportMyReferral(User $user)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function printMyReferral(User $user)
    {
        $moduleName='MyReferral';
        $permission=explode('My', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
  
    public function viewAnyCommission(User $user)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function viewCommission(User $user, Referral $commission=null)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function createCommission(User $user)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function updateCommission(User $user, Referral $Referral=null)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission,$Referral);
    }

    public function deleteCommission(User $user, Referral $Referral=null)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission,$Referral);
    }

    public function restoreCommission(User $user, Referral $Referral)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function forceDeleteCommission(User $user, Referral $Referral)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function importCommission(User $user)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function exportCommission(User $user)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
    public function printCommission(User $user)
    {
        $moduleName='Commission';
        $permission=explode('Commission', __FUNCTION__)[0];
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }

    public function viewAnyReport(User $user)
    {
        $moduleName='ReferralReport';
        $permission='viewAny';
        if(PolicyHelper::isSuperAdmin($user)){return true;}
        return PolicyHelper::policy($user,$moduleName,$permission);
    }
}
