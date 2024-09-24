<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    protected $guard_name = 'web';
    protected $root_folder = "admin";

    public function __construct()
    {
        parent::__construct();
    }

    protected function guard()
    {
        return Auth::guard($this->guard_name);
    }
}
