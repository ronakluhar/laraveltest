<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use Redirect;
use Illuminate\Validation\Rule;
use Config;
use Activity;
use Auth;
Use Helpers;
Use App\Audit;


class AuditController extends Controller
{
    /**        
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('activity');
        $this->controller = 'AuditController';       
        $this->audit = new Audit();
        $this->user = new User();
    }

    /*
     * Get audit log from Database     
     */
    public function getAuditLog()
    {        
        $auditData = $this->audit->getData();         
        return view('admin.audit-log-list',compact('auditData'));
    }

    /*
     * Audit log chart for most active users
     */
    public function auditChart()
    {
        // Get user data for chart
        $users = $this->user->getUserData();
        
        // Handle post data
        $postChart = Input::get('chart');
        if(isset($postChart) && $postChart != ''){
           $chart = Input::get('chart');
        }else{
           $chart = 'column';
        }
               
        $auditLog = $this->audit->getMostActiveUser();
        $mostActiveUsers = $mostActiveUsersJson = array();
        if(isset($auditLog) && !empty($auditLog))
        {
            foreach($auditLog as $key=>$user){
                $mostActiveUsers[$user->name] = $user->total;
            }
        }
        
        //Prepare array for chart
        if(isset($mostActiveUsers) && !empty($mostActiveUsers)){
            foreach($mostActiveUsers as $user=>$count){
                $mostActiveUsersJson[] = array('y' => $count, 'name' => $user);
            }   
        }
        $mostActiveUsersJson = json_encode($mostActiveUsersJson);                
        
        return view('admin.audit-log-chart',compact('mostActiveUsersJson','chart','users'));
    }
}

