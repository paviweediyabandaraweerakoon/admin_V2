<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PasswordPolicyService
{
	protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        Log::channel('password')->info("Password Policy Service Initialed for $user->id");
    }

    protected function userId()
    {
        return $this->user->id;
    }

    
    protected function savePassword($password)
    {
        $userId = $this->userId();
        Log::channel('password')->info("New Password Change $userId");
    }

    public function currentPasswordCount()
    {
        return DB::table('password_histories')->whereUserId($this->userId())->count();
    }

    protected function passwordHistoryLimit()
    {
        return config('password-history.keep');
    }

    public function passwordChangeProcess()
    {
        $userId = $this->userId();
        $currentPasswordCount = $this->currentPasswordCount();
        $passwordHistoryLimit = $this->passwordHistoryLimit();
        Log::channel('password')->info("USER ID : $userId  CURRENT PASSWORD COUNT : $currentPasswordCount PASSWORD HISTORY LIMIT $passwordHistoryLimit");
    }
}
