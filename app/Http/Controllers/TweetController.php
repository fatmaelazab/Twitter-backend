<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use JWTAuthException;
use Auth;
use Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;


class TweetController extends Controller
{
    /**
     * @var Tweet
     */
    private $tweet;
    /**
     * TweetController constructor.
     * @param Tweet $tweet
     */
    public function __construct(Tweet $tweet){
        $this->tweet = $tweet;
    }
    public function postTweet(Request $request){

        $validator = Validator::make($request->all(),[
            'tweet'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],400);
        }
        $tweet= $this->tweet->create([
            'user_id' =>auth()->id(),
            'tweet'=>$request->get('tweet'),
        ]);
        return response()->json(['status'=>true,'message'=>'Tweet Posted successfully','data'=>$tweet],200);
    }

    public function getTweets(Request $request){
        $tweets = DB::table('users')
        ->select('users.username','tweets.tweet','tweets.created_at')
        ->join('tweets','tweets.user_id','=','users.id')
        ->orderBy('tweets.created_at','desc')
        ->get();
        
        return response()->json(['status'=>true,'data'=>$tweets],200);
    }


}
