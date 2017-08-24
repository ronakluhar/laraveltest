<?php

namespace App\Http\Controllers\Webservice;

use File;
use Image;
use Input;
use Auth;
use DB;
use Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class WebserviceController extends Controller {

    public function __construct() {
        $this->servicesBeforeLogin = array();
    }        
}