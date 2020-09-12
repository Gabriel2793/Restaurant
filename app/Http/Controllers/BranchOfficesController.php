<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use DB;

class BranchOfficesController extends BaseController
{
    function index() {
        $points=DB::select('select ST_AsGeoJSON(branch_office) as point, user_id, image, restaurant_name, type FROM branch_offices');
        // var_dump($points[0]);
        // 'data:'+image.mimetype+';base64,'+image.buffer
        return view( 'BranchOffices', [ 'nombre' => 'Gabriel', 'points' => $points ] );
    }
}
