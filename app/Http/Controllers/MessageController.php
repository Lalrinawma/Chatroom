<?php

namespace App\Http\Controllers;

use App\Events\NewMesage;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //
    public function fetch()
    {
        return Message::all();
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'message' => $request->message,
            'user_id' => $request->user()->id
        ]);

        broadcast(new NewMesage($message));

        return redirect()->back();
    }
}
