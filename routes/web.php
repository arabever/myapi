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

      $dochtml = new DOMDocument();
      $client = new Client(HttpClient::create(['timeout' => 60]));
      $c = $client->request('GET', 'https://codeforces.com/submissions/Moustafa.');
      $rows =$c->filter('.status-frame-datatable tr')->count();
      $d=$c->filter('body');
      $data=$d->html();
      $dochtml = new DOMDocument();
      $dochtml->loadHTML($data);
      $td = $dochtml->getElementsByTagName('ul');
      echo $td[3]->nodeValue . '<br>';
  
      //echo $rows;

      $client = new Client(HttpClient::create(['timeout' => 60]));
      $c = $client->request('GET', 'https://codeforces.com/submissions/Moustafa.');
      //$rows =$c->filter('ul:nth-of-type(2)')->html();
      //echo $rows;

      
    $client = new Client(HttpClient::create(['timeout' => 60]));
    $c = $client->request('GET', 'https://codeforces.com/submissions/Moustafa.');
    // $countRows = $c->filter('.status-frame-datatable tr')->count();
    // for($i=2;$i<=$countRows;$i++){
    //     $subtext='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(1)';
    //     $problemText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(4)';
    //     $langText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(5)';
    //     $verdictText='.status-frame-datatable tr:nth-child(' . $i . ') td:nth-child(6)';
    //     $submission=$c->filter($subtext)->text();
    //     $problem=$c->filter($problemText)->text();
    //     $lang=$c->filter($langText)->text();
    //     $verdict=$c->filter($verdictText)->text();
    //     echo $submission . ' ' . $problem . ' ' . $lang . ' ' . $verdict;
    //     echo "\n";
    // }
    return [];
});

Route::get('/trial', function () {
    $client = new Client(HttpClient::create(['timeout' => 60]));
    $crawler = $client->request('GET', 'https://codeforces.com/submissions/zakaria.samy');
    $text = $crawler->filter('tr:nth-child(3) .view-source')->text();
    $link = $crawler->selectLink($text)->link();
    $c = $client->click($link);
    $c->filter('pre')->each(function ($n) {
        $data=$n->html();
        echo str_replace("?","",$data);
    });
    echo '';

    return [];
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
