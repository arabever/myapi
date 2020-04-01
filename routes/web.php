<?php

use App\ProtectBlock;
//use Goutte\Client;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use App\GetSubNewUser;
use App\submission;
use App\User;
use App\TempCurrentUser;
use App\EmptyUser;
use App\FirstSubSuccessScrap;
use App\Http\Controllers;

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
    return view('get');
});
Route::get('/getsub', function () {
    return view('get');
});
Route::get('/a', function () {

    $link='arabever';
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', "https://codeforces.com/api/user.info?handles={$link}",['http_errors' => false]);
    if($res->getStatusCode()==200)
        echo 'succeed';
    else
        echo 'no';
        //get code link
       // $codeLinkString='https://codeforces.com/contest/' . $contestNumber . '/submission/' . $submission;
       // $coldLink= $client->request('GET', $codeLinkString);
        //$data = $coldLink->filter('pre')->html();
        //echo $submission . ' ' . $problem . ' ' . $lang . ' ' . $verdict;
        //echo "\n";
        //return $response;
});

Route::get('/trial', function () {    
    $client = new GuzzleHttp\Client([
       // 'timeout' => 10,
        'base_uri' => 'https://codeforces.com/',
        'proxy' => [
            'http'  => 'https://45.77.202.16:8118', // Use this proxy with "http"
            'https' => 'https://45.77.202.16:8118', // Use this proxy with "https",
        ],
        'headers' => [
            'Host'=>'www.codeforces.com',
            'User-Agent' => ProtectBlock::getUserAgent(),
            'Referer'=>'http://codeforces.com',
            'Connection'=> 'Keep-Alive',
            'Cache-Control'=> 'max-age=0',
            ]
    ]);
    //$client->setUserAgent(UserAgent::getUserAgent());
    $response = $client->request('GET', 'arabever');
    $content = (string)$response->getBody();
    $body = new Crawler($content);
      $pageNumber=1;
      $condition=true;
      $temp='';
    $countRows = $body->filter('.status-frame-datatable tr')->count();
    for($i=2;$i<=4;$i++){
        $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
        $problem=$body->filter($problemText)->text();

        echo $problem;

        //echo $submission . ' ' . $problem . ' ' . $lang . ' ' . $verdict;
        //echo "\n";
    }
    return [];
});


Auth::routes();

Route::get('/home', 'RegisterController@register');
Route::get('/home', 'HomeController@index')->name('home');
