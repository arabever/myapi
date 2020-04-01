<?php

namespace App\Http\Controllers\API;

use App\User;
use App\GetSubNewUser;
use App\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Airlock\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\ProtectBlock;
use App\EmptyUser;
use GuzzleHttp\Client;
class RegisterController extends Controller
{
    public function register(Request $request){
        $link=$request->name;
        $client = new Client();
        $res = $client->request('GET', "https://codeforces.com/api/user.info?handles={$link}",['http_errors' => false]);
        if($res->getStatusCode() !=200)
            return response()->json(['message' => 'This handle doesn\'nt exist at codeforces']);

        $validator= Validator::make($request->all(),
        ['email'=>'required|email|string|unique:users',
        'name'=>'required|string',
        'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name'=>$request->name,
            'password'=>
            Hash::make($request->password)
            ,
            'email'=>$request->email,
            ]);


        $sub = GetSubNewUser::create([
                'user_id'=>$user->id
                ]);
        EmptyUser::create([
                    'user_id'=>$user->id
                    ]);
        $token = $user->createToken('user',['public'])->plainTextToken;
        
        return response()->json(compact('token'));
    
    }
}
