<?php

namespace App\Http\Controllers;
use Symfony\Component\DomCrawler\Crawler;
//use Symfony\Component\HttpClient\HttpClient;
//use Goutte\Client;
use Illuminate\Http\Request;
use App\User;
use GuzzleHttp\Client;
use App\GetSubNewUser;
use App\Submission;
use App\TempCurrentUser;
use App\EmptyUser;
use App\FirstSubSuccessScrap;
use App\ProtectBlock;
class SubmissionController extends Controller
{


    function string_between_two_string($str, $starting_word, $ending_word){ 
        $arr = explode($starting_word, $str); 
        if (isset($arr[1])){ 
            $arr = explode($ending_word, $arr[1]); 
            return $arr[0];
        } 
        return ''; 
      }
    public function getNewUser(){ // will send request to get All Submissions For every User
        foreach (GetSubNewUser::all() as $UserNeedSub){
            $user = $UserNeedSub->user;
            $firstSub=$user->firstSub;
            $this->getNewSub($user->name,$user->id,$user->submissions,$firstSub);
}
    }

    public function getCurrentUser(){
            $users = User::all();
            foreach($users as $user){
                if(empty($user->initialSub)){
                    $firstSub = $user->firstSub->submission;
                    $this->getNewSubOldUsers($user->name,$user->id,$user->submissions,$firstSub);
                }
            }
            return "All Done";
    }

    public function getAllSub($name,$id){

        // Register For register Controller
      // Get all user's submissions and add it to database
        // ***************************************************** 
          //echo $rows;
          $pageNumber=1;
          $condition=true;
          $temp='';
          $counter=0; // Counter to pull first request in success scrap
          while($condition){

            $client = new Client([
                // 'timeout' => 10,
                'base_uri' => 'https://codeforces.com/',
                'headers' => [
                     'Host'=>'www.codeforces.com',
                     'User-Agent' => ProtectBlock::getUserAgent(),
                     'Referer'=>'http://codeforces.com',
                     'Connection'=> 'Keep-Alive',
                     'Cache-Control'=> 'max-age=0',
                     'Accept-Encoding'=>'gzip, deflate',
                      'Accept-Language'=>'en-US,en;q=0.9',
                     ],
                'delay'=>rand(2000,7000),
             ]);
             $requestLink='submissions/ ' .  $name  .   '/page/' . $pageNumber;
             $req1 = $client->request('GET', $requestLink);
             $content = (string)$req1->getBody();
             $c = new Crawler($content);
        $countRows = $c->filter('.status-frame-datatable tr')->count();
        if($countRows<2){
            $condition=false;
        break;
        }
        for($i=2;$i<=4;$i++){ // $countRows
            $subtext='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(1)';
            $submission=$c->filter($subtext)->text();
            if($i==2){
                if($temp == $submission){
                User::find($id)->initialSub->delete(); // Make the user Old Not new
                $condition=false;
                break;
                }
                $temp=$submission;
            }
            $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
            $link= $this->string_between_two_string($c->filter($problemText)->html(),'"','"');
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
            $codeLinkString='contest/' . $contestNumber . '/submission/' . $submission;
            $req= $client->request('GET', $codeLinkString);
            $content = (string)$req->getBody();
            $coldLink = new Crawler($content);
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
                'user_id'=>$id,
                ]);
                
            // if($counter==0){
            //     FirstSubSuccessScrap::create([
            //         'user_id'=>$id,
            //         'submission_id'=>$submissionInDatabase->id,
            //     ]);
            //     $counter++;
            }
        }
        $pageNumber++;
    }

    public function getNewSub($name,$id,$allSubmissionsDB,$firstSub){
        // Register For register Controller
      // Get all user's submissions and add it to database
        // ***************************************************** 
          //echo $rows;
          $pageNumber=1;
          $condition=true;
          $temp='';
          $counter=0;
          while($condition){
             $client = new Client([
                 'base_uri' => 'https://codeforces.com/',
                 'headers' => [
                     'Host'=>'www.codeforces.com',
                     'User-Agent' => ProtectBlock::getUserAgent(),
                     'Referer'=>'http://codeforces.com',
                     'Connection'=> 'Keep-Alive',
                     'Cache-Control'=> 'max-age=0',
                     'Accept-Encoding'=>'gzip, deflate',
                     'Accept-Language'=>'en-US,en;q=0.9',
                 ],
                 'delay'=>rand(2000,7000),
             ]);
             $requestLink='submissions/ ' .  $name  .   '/page/' . $pageNumber;
             $req1 = $client->request('GET', $requestLink);
             $content = (string)$req1->getBody();
             $c = new Crawler($content);
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
                User::find($id)->initialSub->delete(); // Make the user Old Not new
                $condition=false;
                break;
                }
                $temp=$submission;
            }
            $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
            $link= $this->string_between_two_string($c->filter($problemText)->html(),'"','"');
            if(explode('/',$link)[1] != 'contest'){
                continue;
            }
            $contestNumber=explode('/',$link)[2];
            $langText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(5)';
            $verdictText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(6)';
            $problem=$c->filter($problemText)->text();
            // Get submission from DB then Check if it like what we have extracted
            $subDB= $allSubmissionsDB->where('sub',$submission)->where('name',$problem)->first();
            if(!empty($subDB))
            continue;
            $lang=$c->filter($langText)->text();
            $verdict=$c->filter($verdictText)->text();
            //get code link
            $codeLinkString='contest/' . $contestNumber . '/submission/' . $submission;
            $req= $client->request('GET', $codeLinkString);
            $content = (string)$req->getBody();
            $coldLink = new Crawler($content);
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
                'user_id'=>$id,
                ]);
            if($counter==0){
                if($firstSub !=''){
                User::find($id)->firstSub->update([
                    'submission_id'=>$submissionInDatabase->id,
                ]);
                }
                else{
                FirstSubSuccessScrap::create([
                    'user_id'=>$id,
                    'submission_id'=>$submissionInDatabase->id,
                ]);  
                }
                $counter++;
            }
        }
        $pageNumber++;
    }
    }

    public function getNewSubOldUsers($name,$id,$allSubmissionsDB,$firstSub){
        // Register For register Controller
      // Get all user's submissions and add it to database
        // ***************************************************** 
          //echo $rows;
          $pageNumber=1;
          $condition=true;
       //   TempCurrentUser::create([
            //'user_id'=>$id,
          //  'submission_id'=>
        //]);
          $temp='';
          $idFirst=-1; // Save id of first submission so if the scrap succeded it will be added to first_sub_success_scrap
          $counter=0;
          while($condition){
             $client = new Client([
                 'base_uri' => 'https://codeforces.com/',
                 'headers' => [
                     'Host'=>'www.codeforces.com',
                     'User-Agent' => ProtectBlock::getUserAgent(),
                     'Referer'=>'http://codeforces.com',
                     'Connection'=> 'Keep-Alive',
                     'Cache-Control'=> 'max-age=0',
                     'Accept-Encoding'=>'gzip, deflate',
                     'Accept-Language'=>'en-US,en;q=0.9',
                 ],
                 'delay'=>rand(2000,7000),
             ]);
             $requestLink='submissions/ ' .  $name  .   '/page/' . $pageNumber;
             $req1 = $client->request('GET', $requestLink);
             $content = (string)$req1->getBody();
             $c = new Crawler($content);
        $countRows = $c->filter('.status-frame-datatable tr')->count();
        if($countRows<2){
            $condition=false;
            break;
            if($idFirst!=-1){
                User::find($id)->firstSub->update([
                    'submission_id'=>$idFirst,
                ]);
            }
        }
        for($i=2;$i<=$countRows;$i++){
            
            $subtext='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(1)';
            $submission=$c->filter($subtext)->text();
            if($i==2){
                if($temp == $submission){
                $condition=false;
                if($idFirst!=-1){
                    User::find($id)->firstSub->update([
                        'submission_id'=>$idFirst,
                    ]);
                }
                break;
                }
                $temp=$submission;
            }
            $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
            $link= $this->string_between_two_string($c->filter($problemText)->html(),'"','"');
            if(explode('/',$link)[1] != 'contest'){
                continue;
            }
            $contestNumber=explode('/',$link)[2];
            $langText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(5)';
            $verdictText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(6)';
            $problem=$c->filter($problemText)->text();
            // Check if submission equal first submission in last success scraping
            if($problem==$firstSub->name && $submission==$firstSub->sub){
                if($idFirst!=-1){
                    User::find($id)->firstSub->update([
                        'submission_id'=>$idFirst,
                    ]);
                }
                break;
            }
            // Get submission from DB then Check if it like what we have extracted
            $subDB= $allSubmissionsDB->where('sub',$submission)->where('name',$problem)->first();
            if(!empty($subDB))
            continue;
            $lang=$c->filter($langText)->text();
            $verdict=$c->filter($verdictText)->text();
            //get code link
            $codeLinkString='contest/' . $contestNumber . '/submission/' . $submission;
            $req= $client->request('GET', $codeLinkString);
            $content = (string)$req->getBody();
            $coldLink = new Crawler($content);
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
                'user_id'=>$id,
                ]);
            if($counter==0){
                $idFirst=$submissionInDatabase->id;
            }
        }
        $pageNumber++;
    }
    }
}
