<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use App\Saucer;

class AddSaucerController extends BaseController
{
    function index( Request $request ) {
        $file = file_get_contents($request->file('SaucerImage')->getRealPath());
        $saucer = new Saucer;
        $saucer->name=$request->input("SaucerName");
        $saucer->description=$request->input("SaucerDescription");
        $saucer->image=$file;
        $saucer->type=$request->file('SaucerImage')->getMimeType();
        $saucer->price=$request->input('SaucerPrice');
        $saucer->save();
        $success = true;
        return redirect( 'admin/saucerform')->with(['success'=>$success]);
        // var_dump($request->file('SaucerImage')->getMimeType());
    }
}
