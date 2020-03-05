<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result,$message){
        $respone=[
            'success'=>true,
            'data'=>$result,
            'message'=>$message
        ];
        return response()->json($respone,200);
        
    }
    
    public function sendError($error,$errorMessage= []){
        $respone=[
            'success'=>false,
            'message'=>$error,
        ];
        if(!empty($errorMessage)){
            $respone['data']=$errorMessage;
        }
        return response()->json($respone,404);
        
    }
}
