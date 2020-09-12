<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;

class AdminController extends BaseController
{
    function index( Request $request) {
        return var_dump( $request->file );
        // return view( 'Home', [ 'nombre' => 'Gabriel', 'points' => $points ] );
    }
}
