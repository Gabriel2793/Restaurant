<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Saucer;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index( )
    {
        $count = DB::select('select count(*) count from saucers');

        // $saucers = DB::select('select sa.id, sa.name name, sa.image image, sa.description description, sa.type type, sa.created_at created_at, sa.price price
        //                        from ( select os.saucer_id
        //                               from orders as o join order_saucers as os on o.id = os.order_id
        //                               where o.user_id = ? and o.action = "taken"
        //                             ) as os
        //                             right join saucers as sa on os.saucer_id = sa.id
        //                         where os.saucer_id is null
        //                         limit ?,9
        //                     ', [ \Auth::user()->id, 0 ] );

        // $order = DB::select('select o.id order_id, s.name name, s.price price, os.saucer_id saucer_id, os.count count, s.price*os.count total
        //                      from orders as o, order_saucers as os, saucers as s
        //                      where o.id = os.order_id
        //                           and os.saucer_id = s.id
        //                           and o.action="taken"
        //                           and o.user_id=?', [ \Auth::user()->id ]);

        // $pages = ceil(( $count[0]->count - sizeof( $order ) )/9);
        // var_dump( ( $order ) );
        return view( 'home', [ 'saucers'=>$count[0] ] );
    }
}
