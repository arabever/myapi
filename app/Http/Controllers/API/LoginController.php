<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class LoginController extends Controller
{
    public function login(Request $request){

        $validator= \Validator::make($request->all(),
        [
        'email'=>'required|string',
        'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::where('email', $request->email)->first();

    if (! $user) {
        return response()->json(['email' => 'The provided Email is incorrect.']);
    }

    if (! Hash::check($request->password, $user->password)) {
        return response()->json(['email' => 'The provided password is incorrect.']);
    }

    $token = $user->createToken('user',['public'])->plainTextToken;
    return response()->json(compact('token'));
    
    }
}
