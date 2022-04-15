<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function create(User $user, Alert $alert)
    {
        try {


            $userAlerts = $user->alerts()->get();
            if (count($userAlerts) >= 10) {
                return false;
            }
            return true;
        }catch (\Exception $e){
            dd($e);
        }
    }
}
