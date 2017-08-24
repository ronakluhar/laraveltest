<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use Redirect;
use Illuminate\Validation\Rule;
use Image;
use Config;
use File;
use Activity;
use Auth;
Use Helpers;

class UsersController extends Controller
{
    /**        
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->userOriginalImageUploadPath = Config::get('constant.USER_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->userThumbImageUploadPath = Config::get('constant.USER_THUMB_IMAGE_UPLOAD_PATH');
        $this->userThumbImageHeight = Config::get('constant.USER_THUMB_IMAGE_HEIGHT');
        $this->userThumbImageWidth = Config::get('constant.USER_THUMB_IMAGE_WIDTH');
        $this->middleware('activity');
        $this->controller = 'UsersController';   
        $this->user = new User();
    }

    /*
     * Get User from Database     
     */
    public function getUser()
    {
        $uploadUserThumbPath = $this->userThumbImageUploadPath;
        $users = $this->user->getUserData();
        
        //Fetch user last activity time in minute format
        if(isset($users) && !empty($users))
        {
            $users = $users->toArray();
            foreach($users as $key=>$value)
            {
               $lastLogin = Helpers::getUsetLastActivityTime($value['last_activity']); 
               $users[$key]['onlinestatus'] = $lastLogin;
            }
        }        
        //Create Audit Log
        Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin, Config::get('constant.AUDIT_ACTION_READ'), $this->controller . "@index", $_SERVER['REQUEST_URI'], '', '', $_SERVER['REMOTE_ADDR']);
        return view('admin.user-list',compact('users','uploadUserThumbPath'));
    }
    
    /**     
     *
     * @return \Illuminate\Http\Response
     */
    public function addUser()
    {   
        $uploadUserThumbPath = $this->userThumbImageUploadPath;
        
        //Create Audit Log        
        Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin, Config::get('constant.AUDIT_ACTION_READ'), $this->controller . "@addUser", $_SERVER['REQUEST_URI'],'', '', $_SERVER['REMOTE_ADDR']);        
        return view('admin.add-user',compact('uploadUserThumbPath'));
    }
    
    /*
     * Save User
     * Post request 
     */
    public function saveUser()
    {
        $this->validate(request(),[
           'name' => 'required', 
           'email' => ['required','email',Rule::unique('users','email')->ignore(Input::get('id'))],
           'phone' => 'required'
        ]); 
        
        $user = new User();
        $postData = Input::all();   
              
        $hiddenProfile = Input::get('hidden_profile');
        $postData['photo'] = $hiddenProfile;
        if (Input::file()) 
        {
            $file = Input::file('photo');            
            if (!empty($file)) {   
                $ext = $file->getClientOriginalExtension();
                $validImageExtArr = array('jpg', 'jpeg', 'png', 'bmp', 'PNG');                
                if (in_array($ext, $validImageExtArr)) 
                {                
                    $fileName = 'user_' . time() . '.' . $file->getClientOriginalExtension();
                    $pathOriginal = public_path($this->userOriginalImageUploadPath . $fileName);
                    $pathThumb = public_path($this->userThumbImageUploadPath . $fileName);
                    Image::make($file->getRealPath())->save($pathOriginal);
                    Image::make($file->getRealPath())->resize($this->userThumbImageWidth, $this->userThumbImageHeight)->save($pathThumb);  

                    if ($hiddenProfile != '' && $hiddenProfile != "default.png") 
                    {
                        $imageOriginal = public_path($this->userOriginalImageUploadPath . $hiddenProfile);
                        $imageThumb = public_path($this->userThumbImageUploadPath . $hiddenProfile);
                        if(file_exists($imageOriginal) && $hiddenProfile != ''){File::delete($imageOriginal);}
                        if(file_exists($imageThumb) && $hiddenProfile != ''){File::delete($imageThumb);}
                    }                    
                    $postData['photo'] = $fileName;         
                }
            }
        }
        
        if(isset($postData['id']) && $postData['id'] > 0)
        {
           unset($postData['_token']); 
           unset($postData['hidden_profile']); 
           $this->user->saveUserData($postData['id'],$postData);           
           //Create Audit Log
           Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin,Config::get('constant.AUDIT_ACTION_UPDATE'), $this->controller . "@saveUser", $_SERVER['REQUEST_URI'],trans('labels.userupdatesuccess'), serialize($postData), $_SERVER['REMOTE_ADDR']);            
           return Redirect::to("/admin/users/")->with('success', trans('labels.userupdatesuccess'));
           exit;
        }
        else
        {
           $postData['password'] = bcrypt($postData['password']); 
           $this->user->saveUserData($postData['id'],$postData);    
           //Create Audit Log
           Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin,Config::get('constant.AUDIT_ACTION_UPDATE'), $this->controller . "@saveUser", $_SERVER['REQUEST_URI'],trans('labels.useraddsuccess'), serialize($postData), $_SERVER['REMOTE_ADDR']);            
           return Redirect::to("/admin/users/")->with('success', trans('labels.useraddsuccess'));
           exit;
        }    
    }
    
    /*
     * Edit User
     * $id : id
     */
    public function editUser($id)
    {
        $user = $this->user->getUserById($id);
        $uploadUserThumbPath = $this->userThumbImageUploadPath; 
        //Create Audit Log
        Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin, Config::get('constant.AUDIT_ACTION_READ'), $this->controller . "@editUser", $_SERVER['REQUEST_URI'],'', '', $_SERVER['REMOTE_ADDR']);        
        return view('admin.add-user',compact('user','uploadUserThumbPath'));
    }
    
    /*
     * Delete User
     * $id : id
     */
    public function deleteUser($id)
    {
        $user = $this->user->deletUser($id);
        //Create Audit Log
        Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin, Config::get('constant.AUDIT_ACTION_DELETE'), $this->controller . "@deleteUser", $id, trans('labels.userdeletesuccess'), '', $_SERVER['REMOTE_ADDR']);
        return Redirect::to("/admin/users/")->with('success', trans('labels.userdeletesuccess'));
        exit;
    }
}
