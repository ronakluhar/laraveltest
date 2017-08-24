<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','unique_id','photo','last_activity','current_login_time','is_login',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /*
     * Save user each activity in database
     */
    function SaveUserActivity($userId)
    {
        User::where('id', $userId)->update(['last_activity' => strtotime(date('Y-m-d H:i:s'))]);
    }
    
    /*
     * Get user data
     */
    public function getUserData()
    {
        $data = $this->where('is_admin',0)->get();        
        return $data;
    }
    
    /*
     * Save user data
     */
    public function saveUserData($id,$saveData)
    {       
        if(isset($id) && $id > 0)
        {
           $this->where('id',$id)->update($saveData); 
        }
        else
        {
           $this->create($saveData); 
        }
    }
    
    /*
     * Find user by id
     */
    public function getUserById($id)
    {
        $data = $this->find($id);        
        return $data;
    }
    
    
    /*
     * Delete user by id
     */
    public function deletUser($id)
    {
        $data = $this->getUserById($id);
        $data->delete();        
    }
}
