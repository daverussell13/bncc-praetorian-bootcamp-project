<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function postRegister(Request $request)
  {
    $request->validate([
      "name" => "required|min:3|max:40",
      "email" => "required|unique:users,email|ends_with:@gmail.com",
      "phone" => "required|starts_with:08|numeric",
      "password" => "required|min:6|max:12",
      "cpassword" => "required|same:password",
    ]);

    $user = User::create([
      "name" => $request->input("name"),
      "email" => $request->input("email"),
      "phone" => $request->input("phone"),
      "password" => Hash::make($request->input("password"))
    ]);

    if (!$user) return redirect()->back()->with("Failed", "Something went wrong");
    return redirect()->back()->with("Success", "Your account has been registered");
  }

  public function postLogin(Request $request)
  {
    $request->validate([
      "email" => "required|email",
      "password" => "required"
    ]);

    $creds = $request->only("email", "password");

    if (Auth::guard("web")->attempt($creds)) {
      $request->session()->regenerate();
      return redirect()->intended(route("user.home"));
    }

    return redirect()->back()->with("Fail", "Invalid Credentials");
  }

  public function logout(Request $request)
  {
    Auth::guard("web")->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route("user.login");
  }
}
