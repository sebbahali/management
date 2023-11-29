<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Post;
use App\Models\Task;
use Livewire\Component;
use Illuminate\Support\Str;

use Livewire\WithFileUploads;
use Livewire\Attributes\On; 

class Create extends Component
{
  
    public $message , $user_id;
    public $task_id ;
    public $sender_type = "admin";
    public  $resiver_type  = "user";

    public function rules()
    {
        return [
            'message' => 'required',
            'task_id' => 'required',
            'sender_type' => 'required',
            'resiver_type' => 'required',

        ];
    }
   

    public function mount($task_id)
    {
       $this->task_id = $task_id;
    }
public function submitMessage(){

   
$data=$this->validate();

$message =Message::create($data);

broadcast(new MessageSent($message))->toOthers();

//$this->dispatch('messageReceived');
//$this->reset(['content']); 
}


    public function render()
    {
      //  $task = Task::with('users')->where('id', 16)->first();

        return view('livewire.create');
    }
}
