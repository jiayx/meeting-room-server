<?php
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

Route::get('/', function () {
    return view('welcome');
});

// 后台管理
// Route::group(['prefix' => 'admin'], function () {
    // Route::get('{path?}', 'Admin\IndexController@home')->where('path', '[\/\w\.-]*');
// });

Route::get('admin/login', 'Admin\LoginController@index');
Route::post('admin/login', 'Admin\LoginController@login');
Route::get('admin/logout', 'Admin\LoginController@logout');

Route::get('admin/reg', 'Admin\LoginController@reg');



// 后端 api
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['check.admin']], function () {
    // 首页
    Route::get('/', 'IndexController@home');

    // 导出预订记录
    Route::get('/bookings/export', 'BookingController@export');
    Route::post('/bookings/export', 'BookingController@export');

    // 会议室
    Route::resource('/meeting_rooms', 'MeetingRoomController');

    // 预订记录
    Route::resource('bookings', 'BookingController');

    Route::get('staff', 'StaffController@index');
    // 上传
    Route::post('staff/upload', 'StaffController@upload');
});