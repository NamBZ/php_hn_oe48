<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Models\User;
use App\Enums\UserStatus;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return User::class;
    }

    public function blockUser($id)
    {
        $user = $this->find($id);
        if ($user) {
            $user->status = UserStatus::BAN;

            return $user->save();
        }

        return false;
    }

    public function unblockUser($id)
    {
        $user = $this->find($id);
        if ($user) {
            $user->status = UserStatus::ACTIVE;

            return $user->save();
        }

        return false;
    }

    //Send notification to user
    public function sendNotify($id, $event)
    {
        $user = $this->find($id);
        if ($user) {
            $user->notify($event);

            return true;
        }

        return false;
    }
}
