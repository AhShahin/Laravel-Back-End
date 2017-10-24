<?php

namespace App\Http\Controllers\Province;

use App\Http\Controllers\ApiController;
use App\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();

        return $this->showAll($provinces, 201);
    }
}
