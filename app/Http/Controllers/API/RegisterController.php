<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Submission;
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
            'name'=>$request->name,
            'password'=>
            Hash::make($request->password)
            ,
            'email'=>$request->email,
            ]);
            
        $token = $user->createToken('user',['public'])->plainTextToken;

        // Get all user's submissions and add it to database
        // *****************************************************

        function string_between_two_string($str, $starting_word, $ending_word){ 
            $arr = explode($starting_word, $str); 
            if (isset($arr[1])){ 
                $arr = explode($ending_word, $arr[1]); 
                return $arr[0]; 
            } 
            return ''; 
          } 
          //echo $rows;
          $pageNumber=1;
          $condition=true;
          $temp='';
          while($condition){
          $client = new Client(HttpClient::create(['timeout' => 1600]));
          $requestLink='https://codeforces.com/submissions/ ' .  $request->name  .   '/page/' . $pageNumber;
          $c = $client->request('GET', $requestLink);
        $countRows = $c->filter('.status-frame-datatable tr')->count();
        if($countRows<2){
            $condition=false;
        break;
        }
        for($i=2;$i<=$countRows;$i++){
            
            $subtext='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(1)';
            $submission=$c->filter($subtext)->text();
            if($i==2){
                if($temp == $submission){
                $condition=false;
                break;
                }
                $temp=$submission;
            }
            $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
            $link= string_between_two_string($c->filter($problemText)->html(),'"','"');
            if(explode('/',$link)[1] != 'contest'){
                continue;
            }
            $contestNumber=explode('/',$link)[2];
            $langText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(5)';
            $verdictText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(6)';
            $problem=$c->filter($problemText)->text();
            $lang=$c->filter($langText)->text();
            $verdict=$c->filter($verdictText)->text();
            //get code link
            $codeLinkString='https://codeforces.com/contest/' . $contestNumber . '/submission/' . $submission;
            $coldLink= $client->request('GET', $codeLinkString);
            $data = $coldLink->filter('pre')->html();
            $code= str_replace("?","",$data);

            
            //Add to database
            $submissionInDatabase = Submission::create([
                'sub'=>$submission,
                'name'=>$problem,
                'lang'=>$lang,
                'code'=>$code,
                'verdict'=>$verdict,
                'link'=>$link,
                'user_id'=>$user->id,
                ]);

            //echo $submission . ' ' . $problem . ' ' . $lang . ' ' . $verdict;
            //echo "\n";
        }
        $pageNumber++;
        if($pageNumber==3){
            $condition=false;
        }
    }
        
        return response()->json(compact('token'));
    
    }
}
