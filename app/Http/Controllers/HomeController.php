<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Jobs\SendMails;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $posts=Post::with(['comments'=>function($q){
            $q->select('id','post_id','comment');
        }])->get();
        return view('home',compact('posts'));
    }

    public function saveComment(Request $request){
        Comment::create([
            'post_id'=>$request->post_id,
            'user_id'=>Auth::id(),
            'comment'=>$request->post_content,
        ]);

        $data=[
            'user_id'=>Auth::id(),
            'user_name'=>Auth::user()->name,
            'comment'=>$request->post_content,
            'post_id'=>$request->post_id,
        ];

        //save notifyin db table
        event(new NewNotification($data));
        return redirect()->back()->with(['success'=>'commented added successfully']);
    }

    public function sendMails(){
        $comments=Comment::chunk(50,function($data){
            dispatch(new SendMails($data));
        });
      return 'hi';
    }
}
