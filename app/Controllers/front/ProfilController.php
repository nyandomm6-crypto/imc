<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;

class ProfilController extends BaseController
{


    public function __construct() {}

    public function index()
    {

        return view('front/profil/index');
    }

    public function objectifs()
    {

        return view('front/profil/objectifs');
    }
}
