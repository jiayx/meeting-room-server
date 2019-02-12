<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class IndexController extends ApiController
{
    public function home()
    {
        return view('admin.home');
    }
}
