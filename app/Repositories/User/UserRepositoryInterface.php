<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    //block user
    public function blockUser($id);

    //unblock user
    public function unblockUser($id);

    //Send notification to user
    public function sendNotify($id, $event);
    
    //findAdmin
    public function findAdmin();
}
