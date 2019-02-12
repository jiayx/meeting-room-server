<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\User;
use App\Services\Ffan;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use EasyWeChat\Foundation\Application;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * 用户相关
 */
class UserController extends ApiController
{
    public function login(Request $request, Application $wechat)
    {
        $this->validate($request, [
            'code' => 'required|string',
            'iv' => 'required|string',
            'encrypted_data' => 'required|string',
        ]);

        $code = $request->input('code');
        $iv = $request->input('iv');
        $encryptedData = $request->input('encrypted_data');

        // dd($code, $iv, $encryptedData);

        $miniProgram = $wechat->mini_program;

        $sessionKey = $miniProgram->sns->getSessionKey($code);

        $weChatUser = $miniProgram->encryptor->decryptData($sessionKey->session_key, $iv, $encryptedData);

        $user = User::updateOrCreate([
            'openid' => $weChatUser['openId']
        ], [
            'nickname' => $weChatUser['nickName'],
            'avatar' => $weChatUser['avatarUrl'],
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->response(compact('token', 'user'));
    }

    public function bind(Request $request, Ffan $ffan)
    {
        $this->validate($request, [
            'wanxin' => 'required|string',
            'password' => 'required|string',
        ]);

        $params = $request->only(['wanxin', 'password']);

        // 给微信测试用的假数据
        if ($params['wanxin'] == 'mockuser') {
            $user = current_user();
            $user->wanxin = 'mockuser';
            $user->name = '测试';
            $user->save();

            return $this->response($user);
        }

        // 验证账号密码
        if ($ffan->rtxVerify($params['wanxin'], $params['password'])) {
            // 获取用户信息
            $staff = Staff::query()->where('wanxin', $params['wanxin'])->first();
            if (! $staff) {
                return $this->error('400', '没有查到此用户，如有疑问请联系 xxx 反馈');
            }

            $user = current_user();
            $user->wanxin = $params['wanxin'];
            $user->name = $staff->name ?: '';
            $user->mobile = $staff->mobile ?: '';
            $user->save();

            return $this->response($user);
        }

        return $this->response(null, '400', '账号或密码错误');
    }

    // 用户详情
    public function show($encryptedId, Ffan $ffan, Encrypter $encrypter)
    {
        try {
            $id = $encrypter->decryptString(base64_url_safe_decode($encryptedId));
        } catch (DecryptException $e) {
            return $this->error('400', '无效的用户 id');
        }

        $user = User::find($id);

        $currentUser = current_user();

        // 绑定了万信号的人才能看到别人的手机号
        if ($currentUser->wanxin && $user->wanxin && $user->wanxin != 'mockuser') {
            // 这个用户没手机号去查一下 兼容之前未存手机号的逻辑
            if ($user->mobile == '') {
                $wanXinUser = $ffan->user($user->wanxin);
                if ($wanXinUser) {
                    $user->mobile = $wanXinUser->mobile ?: '';
                    $user->save();
                }
            }
        } else {
            $user->mobile = '';
        }

        return $this->response($user);
    }

    // 获取用户的会议室预订记录
    public function booking()
    {
        $bookings = Booking::where('user_id', current_user_id())->get();

        return $this->response($bookings);
    }
}
