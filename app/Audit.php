<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
Use App\User;
Use Config;
Use DB;

class Audit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_type', 'action', 'object_type', 'object_id', 'message', 'other', 'ip_address'
    ];
    
    /*
    *    $logData : array of Audit data to be inserted
    */
    public function saveAudit($auditData)
    {
        $row = $this->create($auditData);
        return $row->id;
    }
    
    /*
     * Get Audit Data
     */
    public function getData()
    {
        $data = $this->with('getUserData')->get();        
        return $data;        
    }
    
    /*
     * Fetch username using relationship 
     */
    public function getUserData()
    {
        $data = $this->belongsTo('App\User','user_id');
        return $data;
    }
    
    /*
     * Get most activity users 
     * Group By User Id
     */
    public function getMostActiveUser()
    {
       $activityLog = DB::table(Config::get('databaseconstants.TBL_AUDIT').' as audit')
                 ->join(config::get('databaseconstants.TBL_USERS') . " as user", 'audit.user_id', '=', 'user.id')                
                 ->select('user.name','audit.user_id',DB::raw('count(audit.id) as total'))
                 ->groupBy('audit.user_id')
                 ->get();
        return $activityLog;
    }
    
}
