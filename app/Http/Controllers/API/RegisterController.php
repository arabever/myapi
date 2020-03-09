<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Airlock\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class RegisterController extends Controller
{
    public function register(Request $request){
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $link='https://codeforces.com/profile/' . $request->name;
        $crawler = $client->request('GET', $link);
        $text = $crawler->filter('.user-rank')->count();

        if($text ==0){
            return response()->json(['message' => 'This handle doesn\'nt exist at codeforces']);
        }

        $validator= Validator::make($request->all(),
        ['email'=>'required|email|string|unique:users',
        'name'=>'required|string',
        'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name'=>$request->email,
            'password'=>
            Hash::make($request->password)
            ,
            'email'=>$request->email,
            ]);
            
        $token = $user->createToken('user',['public'])->plainTextToken;
        
        return response()->json(compact('token'));
    
    }
}
