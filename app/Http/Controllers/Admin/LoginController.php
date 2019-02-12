<?php
/**
 * Created by PhpStorm.
 * User: jiayx
 * Date: 2017/8/1
 * Time: 下午4:39
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LoginController extends ApiController
{
    use AuthenticatesUsers;

    public function index()
    {
        return view('admin.login');
    }

    protected function authenticated(Request $request, $user)
    {
        return $this->response([
            'location' => '/admin'
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->input('remember')
        );
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/admin/login');
    }

    public function reg()
    {
        if (Admin::where('username', 'admin')->count() == 0) {
            Admin::create([
                'name' => '管理员',
                'username' => 'admin',
                'password' => Hash::make('123456'),
            ]);
        }

        return '已创建';
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|max:30',
            'password' => 'required|string|max:30',
            'remember' => 'required|boolean',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        if ($request->expectsJson()) {
            return $this->error('422', '用户名或密码错误');
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}