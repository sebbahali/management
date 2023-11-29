<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BirthdayNotification;
use Illuminate\Support\Carbon;
class AdminController extends Controller
{
public function store(request $request){

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        'password' => ['required'],
 
    ]);   
$admin=Admin::create([
    'name'=>$request->name,
    'email'=>$request->email,
    'password'=>hash::make($request->password),
    ]);
    return redirect()->route('alltask');
}
public static function checkUserBirthdays()
{
    $today = Carbon::now()->format('m-d'); 

    $usersWithBirthdayToday = User::whereRaw("DATE_FORMAT(birthday_date, '%m-%d') = ?", [$today])->get();

    if ($usersWithBirthdayToday->isNotEmpty()) {
        $admin = Admin::all();

        Notification::send($admin, new BirthdayNotification($usersWithBirthdayToday));
    }
}
}
