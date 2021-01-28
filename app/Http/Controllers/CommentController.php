<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Comment;

class CommentController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = $request->user_id;

        $blacklist = DB::table('users')
        ->select('blacklists.blocked_user_id as  blocked user')
        ->join('blacklists', 'users.id', '=', 'blacklists.user_id')
        ->where('blacklists.blocked_user_id',$userId)
        ->get();

        if(count($blacklist)){
            echo "Cant add the comment, you are in blacklist";
           
        }
        else {
            echo 'success';
             return Comment::create($request->all());
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Comment::find($id);
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
        $comment = Comment::find($id);
        $comment->update($request->all());
        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::destroy($id);
        return $comment;
    }
}
