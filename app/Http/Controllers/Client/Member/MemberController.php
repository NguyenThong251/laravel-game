<?php

namespace App\Http\Controllers\Client\Member;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends MemberBaseController
{
    public function __construct()
    {
        $this->member = $this->guard()->user();
        parent::__construct();
    }
}
