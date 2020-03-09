<?php

namespace App\Http\Middleware;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Closure;

class IsAvalible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isset(($_POST['name']))){
            return $next($request);
        }
        
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $link='https://codeforces.com/profile/' . $_POST['name'];
        $crawler = $client->request('GET', $link);
        $text = $crawler->filter('.user-rank')->count();

        if($text !=0){
            return $next($request);
        }

        return redirect('/register')->with('handle','please enter valid handle');

    }
}
