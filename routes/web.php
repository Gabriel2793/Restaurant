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

Route::post( '/addOrder', function ( Request $request ) {
    $saucer =  $request->input();
    $keys = array();
    $matches = array();

    if( sizeof( $request->input() ) === 0 ) {
        $keys = 'empty';
    }else{
        DB::beginTransaction();
            $order_id = DB::table('orders')->insertGetId([
                'user_id' => Auth::id(),
                'action' => 'taken'
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
    }

    return response()->json([ 'success'=>$keys ]);
} )->name('order.add');

Route::post( '/getSoucers', function(Request $request) {
    $saucers = DB::select('select sa.id, sa.name name, sa.image image, sa.description description, sa.type type, sa.created_at created_at, sa.price price
                               from ( select os.saucer_id
                                      from orders as o join order_saucers as os on o.id = os.order_id
                                      where o.user_id = ? and o.action = "taken"
                                    ) as os
                                    right join saucers as sa on os.saucer_id = sa.id
                                where os.saucer_id is null
                                limit ?,9
                            ', [ \Auth::user()->id, $request->input('offset')*9 ] );
    $saucers = array_map(function ($value) {
        $value->image = base64_encode($value->image);
        return $value;
    }, $saucers);

    // var_dump($saucers);

    return response()->json(['saucers'=>($saucers)]);
} )->name('saucers.get');

Route::post( '/updateOrder/{orderid}', function ( $orderid, Request $request ) {
    $keys='hola';
    if( sizeof( $request->input() ) === 0 ) {
        $keys = 'empty';
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
    }

    return response()->json([ 'success'=>$orderid ]);
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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
