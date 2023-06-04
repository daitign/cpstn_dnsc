<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $max_timestamp = Message::max('created_at');
        $timestamp = !empty($request->timestamp) ? $request->timestamp : $max_timestamp;
        if($request->ajax()) {
            if(!empty($request->timestamp)) {
                $messages = Message::where('created_at', '>', $timestamp)->get();
            }else{
                $messages = Message::get();    
            }

            $timestamp = $max_timestamp;
            return compact('messages', 'timestamp');
        }
        return view('messages.index', compact('timestamp'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        Message::create([
            'user_id' => $user->id,
            'message' => $request->message
        ]);
    }
}
