<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\PrivateMessageSent;
use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //FOR GROUP CHAT
    public function fetchMessages()
    {
        //return Message::with('user')->all();
        $allMessage = Message::all();
        return $allMessage->load('user');
    }
    public function sendMessages(Request $request)
    {
        if($request->has('file'))
        {
            $filename = request('file')->store('group_chat');

            $message = Message::create([
                'user_id' => $request->user()->id,
                'image' => $filename,
                'receiver_id' => $request->receiver_id,
            ]);
        }
        else
        {
            $message = auth()->user()->messages()->create(['message'=>$request->message]);
        }

        broadcast(new MessageSent(auth()->user(),$message->load('user')))->toOthers();

        return response(['status'=>'Message sent successfully']);
    }
    //FOR PRIVATE CHAT
    public function fetchPrivateMessages($friendId)
    {
        $privateCommunication = Message::with('user')
                                ->where(['user_id' => auth()->id() , 'receiver_id' => $friendId])
                                //->orWhere(['user_id' => $friendId , 'receiver_id' => auth()->id()])
                                ->orWhere(function($query) use($friendId){
                                     $query->where(['user_id' => $friendId, 'receiver_id' => auth()->id()]);
                                })
                                ->get();
       
        
        return $privateCommunication;
    }
    public function sendPrivateMessages(Request $request,$friendId)
    {
        if($request->has('file'))
        {
            $filename = request('file')->store('private_chat');

            $message = Message::create([
                'user_id' => $request->user()->id,
                'image' => $filename,
                'receiver_id' => $friendId,
            ]);
        }
        else
        {
            $input = $request->all();
            $input['receiver_id'] = $friendId;
            $message = auth()->user()->messages()->create($input);
        }

        broadcast(new PrivateMessageSent($message->load('user')))->toOthers();

        return response(['status'=>'Private Message sent successfully']);
    }
}
