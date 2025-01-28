<?php
namespace App\Helpers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

class UserLogHelper
{
    public static function log($action, $details = null)
    {
        UserLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'details' => $details,
        ]);
    }
}

?>
