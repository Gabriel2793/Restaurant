<?php

use Illuminate\Support\Facades\Route;
use App\Saucer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Order;
use App\OrderSaucer;
use DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( '/', function() {
    $data=Saucer::take( 9 )->get();
    return view('welcome', [ 'saucers' => $data ]);
} )->name("welcome");

$router->get( 'admin/restaurantform', function () {
    if( session('success') === NULL ){
        $data = [];
    }elseif ( session('success') ) {
        $data = ['success' => true];
    }else{
        $data = ['success' => false];
    }

    return view( 'AddRestaurant', $data );
} )->name('restaurant.form');

Route::post( '/addrestaurant', 'AddRestaurantController@index' )->name( 'restaurant.add' );

$router->get( 'admin/saucerform', function () {
    if( session('success') === NULL ){
        $data = [];
    }elseif ( session('success') ) {
        $data = ['success' => true];
    }else{
        $data = ['success' => false];
    }

    return view( 'AddSaucer', $data );
} )->name('saucer.form');

Route::post( '/addsaucer', 'AddSaucerController@index' )->name( 'saucer.add' );

$router->get( '/branchoffices', 'BranchOfficesController@index')->name('restaurant.branchoffices');

Route::post( '/addOrder/{table_number}', function ( $table_number, Request $request ) {
    $saucer =  $request->input();
    $keys = array();
    $matches = array();

    if( sizeof( $saucer ) === 0 ) {
        return response()->json([ 'success'=>false ]);
    }else{
        DB::beginTransaction();
            $order_id = DB::table('orders')->insertGetId([
                'user_id' => Auth::id(),
                'action' => 'taken',
                'table_number' => $table_number
            ]);

            foreach( $saucer as $key => $value ) {
                preg_match("/[0-9]+/",$key, $matches);
                $complete = DB::table('order_saucers')->insert([
                    'order_id' => $order_id,
                    'saucer_id' => $matches[0],
                    'count' => $value
                ]);
            }
        DB::commit();
        return response()->json([ 'success'=>true, 'order_id'=>$order_id ]);
    }
} )->name('order.add');

Route::post( '/getSoucers', function(Request $request) {
    $saucers = DB::select('select sa.id, sa.name name, sa.image image, sa.description description, sa.type type, sa.created_at created_at, sa.price price
                               from ( select os.saucer_id
                                      from orders as o join order_saucers as os on o.id = os.order_id
                                      where o.table_number = ? and o.action = "taken"
                                    ) as os
                                    right join saucers as sa on os.saucer_id = sa.id
                                where os.saucer_id is null
                                limit ?,9
                            ', [ $request->input('table_number'), $request->input('offset')*9 ] );
    $saucers = array_map(function ($value) {
        $value->image = base64_encode($value->image);
        return $value;
    }, $saucers);

    return response()->json(['saucers'=>($saucers)]);
} )->name('saucers.get');

Route::post( '/updateOrder/{orderid}', function ( $orderid, Request $request ) {
    if( sizeof( $request->input() ) === 0 ) {
        DB::beginTransaction();

            DB::delete('delete from order_saucers where order_id=?',[$orderid]);

        DB::commit();
    }else{
        $saucer =  $request->input();
        $matches = array();

        DB::beginTransaction();

            DB::delete('delete from order_saucers where order_id=?',[$orderid]);

            foreach( $saucer as $key => $value ) {
                preg_match("/[0-9]+/",$key, $matches);
                $complete = DB::table('order_saucers')->insert([
                    'order_id' => $orderid,
                    'saucer_id' => $matches[0],
                    'count' => $value
                ]);
            }
        DB::commit();

        $table_number = DB::select('select table_number from orders where id = ?',[$orderid]);
    }

    return response()->json([ 'success'=>$orderid, 'table_number'=>$table_number[0]->table_number ]);
} )->name('order.update');

Route::post( '/cancelOrder/{orderid}', function ( $orderid, Request $request ) {
        $saucer =  $request->input();

        DB::beginTransaction();
            DB::update('update orders set action="canceled" where id=?',[$orderid]);
        DB::commit();

    return response()->json([ 'success'=>$orderid ]);
} )->name('order.cancel');

Route::post( '/orderPayed/{orderid}', function ( $orderid, Request $request ) {
    $saucer =  $request->input();

    DB::beginTransaction();
        DB::update('update orders set action="payed" where id=?',[$orderid]);
    DB::commit();

    return response()->json([ 'success'=>$orderid ]);
} )->name('order.payed');

Route::post('getOrder', function(Request $request){
    $countSaucers = DB::select('select count(*) countSaucers from saucers');

    $saucers = DB::select('select sa.id, sa.name name, sa.image image, sa.description description, sa.type type, sa.created_at created_at, sa.price price
                               from ( select os.saucer_id
                                      from orders as o join order_saucers as os on o.id = os.order_id
                                      where o.table_number = ? and o.action = "taken"
                                    ) as os
                                    right join saucers as sa on os.saucer_id = sa.id
                                where os.saucer_id is null
                                limit ?,9
                            ', [ $request->input('table_number'), 0 ] );

    $saucers = array_map(function ($value) {
        $value->image = base64_encode($value->image);
        return $value;
    }, $saucers);

    $order = DB::select('select o.id order_id, s.name name, s.price price, os.saucer_id saucer_id, os.count count, s.price*os.count total
                            from orders as o, order_saucers as os, saucers as s
                            where o.id = os.order_id
                                and os.saucer_id = s.id
                                and o.action="taken"
                                and o.table_number=?', [ $request->input('table_number') ]
                        );

    $pageCount = ($countSaucers[0]->countSaucers-sizeof($order))/9;

    return response()->json([ 'success'=>true, 'order'=>$order, 'saucers'=>$saucers, 'pageCount'=>$pageCount ]);
})->name('order.get');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post( '/searchForSaucer', function(Request $request) {

    $saucer = $request->input('saucer');
    $order = $request->input('order_id');

    $subquery = DB::table('orders')
                  ->select('order_saucers.saucer_id as saucer_id')
                  ->join('order_saucers','orders.id','=','order_saucers.order_id')
                  ->where('orders.id','=',(int)$order);
    $saucers = DB::table('saucers')
                  ->leftjoinsub($subquery,'subquery', function ($join) {
                      $join->on('saucers.id','=','subquery.saucer_id');
                  })
                  ->where('saucers.name','like',"%{$saucer}%")
                  ->whereNull('subquery.saucer_id')
                  ->get()
                  ->toArray();

    $saucers = array_map(function ($value) {
        $value->image = base64_encode($value->image);
        return $value;
    }, $saucers);

    return response()->json(['saucers'=>($saucers)]);
} )->name('saucers.search');
