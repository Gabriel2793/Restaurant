<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;

class AddRestaurantController extends BaseController
{
    function index( Request $request ) {
        $file = file_get_contents($request->file('RestaurantImage')->getRealPath());
        // var_dump( $file );
        $points=DB::table('branch_offices')
        ->insert(array(
            'branch_office'=>DB::raw("(ST_GeomFromText('POINT(".$request->input('RestaurantLatitude').' '.$request->input('RestaurantLongitude').")'))"),
            'image' => $file,
            'restaurant_name'=>'"'.$request->input("RestaurantName").'"',
            'type'=>'"'.$request->file("RestaurantImage")->getMimeType().'"'
        ));
        $success = true;
        return redirect( 'admin/restaurantform')->with(['success'=>false]);
    }
}
