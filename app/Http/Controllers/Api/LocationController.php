<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends ApiController
{
    public function index()
    {
        $locations = Location::all();

        return $this->response($locations);
    }
}
