<?php

namespace App\Helpers;

use DB;
use Config;
use App\Audit;

Class Helpers {
    
    /*
     * Calculate user last activity and return time in minutes
     * $lastActivityTime : lastActivityTime
     */
    public static function getUsetLastActivityTime($lastActivityTime) {
        $currentTime = strtotime(date('Y-m-d H:i:s'));

        $difference = '';
        
        $difference = round(abs($currentTime - $lastActivityTime) / 60);
        return $difference;
    }
    
    /*
     * Calculate user online status based on last activity time
     * $time : Time
     */
    public static function showUserOnlineStatus($time,$isLogin)
    {
        $return = '';
        if($time <= Config('constant.RED_STATUS') && $isLogin == 1) 
        {
           $return = '<i class="fa fa-circle text-red"></i> Busy';
        }
        elseif($time > Config('constant.RED_STATUS') && $time <= Config('constant.AMBER_STATUS') && $isLogin == 1)
        {
            $return = '<i class="fa fa-circle text-success"></i> Available';
        }
        elseif($time > Config('constant.AMBER_STATUS') && $time <= Config('constant.GREEN_STATUS') && $isLogin == 1)
        {
            $return = '<i class="fa fa-circle text-warning"></i> Away';
        }
        else
        {
            $return = '<i class="fa fa-circle text-default"></i> In Active';
        }
        return $return;      
    }
   
   /*
      @createAudit Parameters
      $userId : LoggedIn User ID
      $userType : LoggedIn User Type
      $action : CREATE, READ, UPDATE, DELETE
      $objectType : Type of object (Table Name) which is added, updated, deleted & (Controller Function Name) for viewed
      $objectId : ID of object (Table Name) which is added, updated, deleted & (URL) for viewed
      $message : message
      $extra : Extra data if any
      $ip : IP of user machine
     */

    public static function createAudit($userId, $userType, $action, $objectType, $objectId, $message = '', $extra = '', $ip = '') {
        $auditData = [];
        $auditData['user_id'] = $userId;
        $auditData['user_type'] = $userType;
        $auditData['action'] = $action;
        $auditData['object_type'] = $objectType;
        $auditData['object_id'] = $objectId;
        $auditData['message'] = $message;
        $auditData['other'] = $extra;
        $auditData['ip'] = $ip;
        $objAudit = new Audit();
        $audit = $objAudit->saveAudit($auditData);
        return $audit;
    }
}