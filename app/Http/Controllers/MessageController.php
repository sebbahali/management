<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Exceptions\Handler;
use App\Models\Message;
use App\Models\Task;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function msg()
    {
        return app(Handler::class)->handleTaskException(function () {
            $tasks = Task::with(['users', 'messages'])->take(200)->get();

            if ($tasks->isEmpty()) {
                Log::warning('No tasks found.');
                return abort(404);
                        }

            return view('msg', compact('tasks'));
        });
    }

    public function msguser()
    {
        return app(Handler::class)->handleTaskException(function () {
            if (auth()->check()) {
                $tasks = auth()->user()->tasks()->with('messages')->take(200)->get();
                return view('msguser', compact('tasks'));
            } else {
                Log::warning('User not authenticated.');
                return abort(404);            }
        });
    }
   public function create(request $request ,$id){
    $data = $request->validate([
        'message' => 'required',
       
    ]);
    $data['task_id'] = $id;
    $data['sender_type'] = 'admin';
    $data['resiver_type'] = 'user';

    $message = Message::create($data);
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]
    );

  
    $pusher->trigger("msgs", 'MessageSent', $message);
    \Log::info('Message sent:', $message->toArray());

   
   }

   public function createuser(request $request,$id){
    
        $data = $request->validate([
            'message' => 'required',
         
        ]);
        $data['task_id'] = $id;
        $data['user_id'] = auth()->user()->id;
        $data['sender_type'] = 'user';
        $data['resiver_type'] = 'admin';

        $message = Message::create($data);

    broadcast(new MessageSent($message))->toOthers();
    
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]
    );

    broadcast(new MessageSent($message))->toOthers();

    $pusher->trigger("msgs", 'MessageSent', $message);
    \Log::info('Message sent:', $message->toArray());

  //  return redirect()->back();

   }
}
