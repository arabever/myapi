<?php

use Illuminate\Support\Facades\Route;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/a', function () {
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
      $requestLink='https://codeforces.com/submissions/HagarAlaa/page/' . $pageNumber;
      $c = $client->request('GET', $requestLink);
    $countRows = $c->filter('.status-frame-datatable tr')->count();
    if($countRows<2){
        $condition=false;
    break;
    }
    for($i=2;$i<=$countRows;$i++){
        
        $subtext='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(1)';
        $submission=$c->filter($subtext)->text();
        $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
        $link= string_between_two_string($c->filter($problemText)->html(),'"','"');
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
            echo str_replace("?","",$data);
        if($i==2){
            if($temp == $submission){
            $condition=false;
            break;
            }
            $temp=$submission;
        }
        echo $submission . ' ' . $problem . ' ' . $lang . ' ' . $verdict;
        echo "\n";
    }
    $pageNumber++;
    if($pageNumber==3){
        $condition=false;
    }
}
    return [];
});

Route::get('/trial', function () {
    $client = new Client(HttpClient::create(['timeout' => 1600]));
    $crawler = $client->request('GET', 'https://codeforces.com/submissions/zakaria.samy');
    $text = $crawler->filter('tr:nth-child(3) .view-source')->text();
    $link = $crawler->selectLink($text)->link();
    $c = $client->click($link);
    $data = $c->filter('pre')->html();
        echo str_replace("?","",$data);
    echo '';

    return [];
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
