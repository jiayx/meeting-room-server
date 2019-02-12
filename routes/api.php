<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 需要登录校验中间件 LoginCheck
Route::group(['namespace' => 'Api', 'middleware' => ['check.login']], function () {
    // 会议室   'store', 'update', 'destroy',
    Route::resource('meeting_rooms', 'MeetingRoomController', [
        'only' => [
            'index', 'show',
        ]
    ]);

    // 预订会议室
    Route::resource('bookings', 'BookingController', [
        'only' => [
            'index', 'show', 'store', 'destroy',
        ]
    ]);

    // 绑定万信号
    Route::post('user/bind', 'UserController@bind');

    // 查看用户信息 通过加密过后的 id
    Route::get('users/{encryptedId}', 'UserController@show');
});

// 无需登录的路由
Route::group(['namespace' => 'Api'], function () {
    // 登录
    Route::post('user/login', 'UserController@login');

    // 获取可预订时间段
    Route::get('schedules', 'MeetingRoomController@schedules');

    // 获取楼号
    Route::get('locations', 'LocationController@index');

    // 意见反馈
    Route::get('feedback', 'FeedbackController@index');
    Route::post('feedback', 'FeedbackController@store');

    // 超级用户
    Route::get('superusers', 'SuperUserController@index');
});
