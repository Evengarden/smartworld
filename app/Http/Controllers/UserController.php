<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;


use App\Models\User;

class UserController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return User::forceCreate([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'api_token' => Str::random(80),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        if($user){
            $userId = $id;
            if($user->id == $userId){
                return User::find($id);
            }
        }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if($user){
            $userId = $id;
            if($user->id == $userId){
                $user = User::find($id);
                $user->update($request->all());
                return $user;
            }
            else{
                return "You cant update someone else's profile";
            }
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $user = User::destroy($id);
    //     return $user;
    // }

    public function Authorization(Request $request){
        $email = $request->email;
        $password = DB::table('users')->where('email',$email)->first()->password;
        if(Hash::check($request->password, $password)){
            $auth = auth()->attempt(['email' => $email, 'password' => $request->password]);
            return $auth;
        }
       
    }

    public function News(Request $request){
        $user = auth()->user();
        if($user){
            $userId = $request->user_id;
            if($user->id == $userId){
                $userId = $request->user_id;
                $news = DB::table('posts')
                ->select('posts.theme as theme','posts.text as text')
                ->join('followers', 'posts.user_id', '=', 'followers.follower_id')
                ->where('followers.follower_id',$userId)
                ->get();
                return $news;
            }
            else{
                return "You cant browse someone else's news";
            }
        }
       
    }

    public function getPosts(Request $request){
        $user = auth()->user();
        if($user){
            $userId = $request->user_id;
            $posts = DB::table('posts')->where('user_id',$userId)->get();
            return $posts;
            
        }
       
    }



    public function getProfileInfo(Request $request){
        $user = auth()->user();
        if($user){
            $userId = $request->user_id;
            $followers = DB::table('users')
            ->select('followers.user_id as user','followers.follower_id as follower')
            ->join('followers', 'users.id', '=', 'followers.user_id')
            ->where('followers.user_id',$userId)
            ->orWhere('followers.follower_id',$userId)
            ->get();
            if($user->id == $userId){
                return $followers;
            }
            else{
                $blacklist = DB::table('users')
                ->select('blacklists.blocked_user_id as  blocked user')
                ->join('blacklists', 'users.id', '=', 'blacklists.user_id')
                ->where('blacklists.blocked_user_id',$userId)
                ->get();
        
                if(count($blacklist)){
                    echo "Cant show profile info, you are in blacklist";
                   
                }
                else {
                    return $followers;
                }
            }
        } 
       
    }
}
