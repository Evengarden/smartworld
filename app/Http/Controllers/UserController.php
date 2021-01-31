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
        return User::find($id);
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
        //
        $user = User::find($id);
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::destroy($id);
        return $user;
    }

    public function Authorization(Request $request){
        $email = $request->email;
        $password = DB::table('users')->where('email',$email)->first()->password;
        if(Hash::check($request->password, $password)){
            $auth = auth('api')->attempt(['email' => $email, 'password' => $request->password]);
            if($auth){
                $authUser= DB::table('users')->where([['email',$email]],[['password',$password]])->first()->id;
                $user = User::find($authUser);
                Auth::login($user);
                echo $auth;
            }
        }
       
    }

    public function News(Request $request){
        $userId = $request->user_id;
        $news = DB::table('posts')
        ->select('posts.theme as theme','posts.text as text')
        ->join('followers', 'posts.user_id', '=', 'followers.follower_id')
        ->where('followers.follower_id',$userId)
        ->get();
        echo $news;
    }

    public function getPosts(Request $request){
        $userId = $request->user_id;
        $posts = DB::table('posts')->where('user_id',$userId)->get();
        echo $posts;
    }



    public function getProfileInfo(Request $request){
        $userId = $request->user_id;
        $followers = DB::table('users')
        ->select('followers.user_id as user','followers.follower_id as follower')
        ->join('followers', 'users.id', '=', 'followers.user_id')
        ->where('followers.user_id',$userId)
        ->orWhere('followers.follower_id',$userId)
        ->get();
        echo $followers;
    }
}
