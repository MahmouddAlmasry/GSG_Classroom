<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        // $user = User::where('email', '=', $request->email)->first();
        // if($user && Hash::check($request->password, $user->password)){
        //     //User Autinticated
        //     Auth::login($user, $request->boolean('remember'));
        //     return redirect()->route('classrooms.index');
        // }

        // هذا الكود يختصر الكود الذي فوقه ويعمل نفس عمله تماما
        $result = Auth::attempt([
            "email" => $request->email,
            "password" => $request->password,
            // "status" => 'active',
            ], $request->boolean('remember'));

        if($result){
            //هذا الكود عند تسجيل الدخول يرسلني الى صفحة الكلاس رووم
            // return redirect()->route('classrooms.index');
            //اما هذا الكود عند تسجيل الدخول يرسلني الى الصفحة التي كنت طالبها في الرابط
            return redirect()->intended('/');
        }


        return back()->withInput()->withErrors([
            'email' => 'Verify your email or password.',
        ]);
    }
}
