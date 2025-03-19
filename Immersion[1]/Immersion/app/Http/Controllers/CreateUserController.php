<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserController extends Controller
{
   
    public function showRegistrationForm()
    {
        return view('register');
    }

    
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'password2' => 'required|string|min:8|same:password',
        ], [
            'password2.same' => 'The confirmation password does not match the password.'
        ]);

       
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

       
        $user = User::create([
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        
        return redirect()->route('home')->with('success', 'Inscription réussie !');
    }
}