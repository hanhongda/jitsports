<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        //只让未登录用户访问登录页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }
    /**
     * 进入登录页面
     */
    public function create()
    {
       return view('sessions.create');
    }

    /**
     * 登录，post表单-提交用户登录信息，进入主页
     */
    public function store(Request $request)
    {
       $credentials = $this->validate($request,[
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);

       if (Auth::attempt($credentials,$request->has('remember'))) {
           session()->flash('success','欢迎回来!');
           //intended方法可将页面重定向到上一次请求尝试访问的页面上，并接受一个默认跳转地址参数
           return redirect()->intended(route('users.show',[Auth::user()]));
       } else {
           session()->flash('danger','sorry,您的邮箱与密码不匹配');
           return redirect()->back();
       }

    }

    /**
     * 退出登录
     */
     public function destroy()
     {
         Auth::logout();
         session()->flash('success', '您已成功退出！');
         return redirect('login');
     }
}
