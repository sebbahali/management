<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message as msg;
use App\Models\Task;
class MessageUser extends Component
{
    public $task_id;
    public $msgs = [];
    public $task;

   // protected $listeners = ['userMessageReceived' => 'updateUserMessages'];

   protected $listeners = ['messageReceived'];

    public function mount($task_id)
    {
       $this->task_id = $task_id;

     //   $this->updateUserMessages(); 
    }
   /* public function updateUserMessages()
    {
     
       

       $this->task=Task::where('id', $this->task_id)->first();

        $this->msgs = msg::where('task_id', $this->task_id)->orderBy('created_at', 'asc')->get();

    }*/
    public function messageReceived($message)
    {
        // Update the messages array with the new message
        $this->msgs[] = $message;
    }
    public function render()
    {
        return view('livewire.message-user');
    }
}
