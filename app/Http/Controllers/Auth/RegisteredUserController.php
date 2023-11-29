<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Temporary;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

use Spatie\MediaLibrary\Support\TemporaryDirectory;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required'], // 'exists:departments,id'
            'start_date' => ['required', 'date'],
            'birthday_date' => ['required', 'date'],
            'avatars' => ['required'],

        ]);
       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'start_date' => $request->start_date,
            'birthday_date' => $request->birthday_date,
        ]);
        $Temporary=Temporary::latest()->first();
if($Temporary){ 
     $user->addmedia(storage_path('app/public/avatars/' . $Temporary->folder . '/' . $Temporary->filename))
->toMediaCollection('realavatars');

                 rmdir(storage_path('app\public\avatars' .'\\'. $Temporary->folder)); //delete the temporary folder
            $Temporary->delete(); // delete the tmp record
}
$user->update([
    'avatars'=>$Temporary->filename,
]);
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
   
    }
}
