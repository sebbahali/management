<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Events\MessageSent;

class CreateUser extends Component
{
    public $message ;
    public $task_id ;
    public $user_id ;
    public $sender_type = "user";
    public  $resiver_type  = "admin";
    public function rules()
    {
        return [
            'message' => 'required',
            'task_id' => 'required',
            'sender_type' => 'required',
            'resiver_type' => 'required',
            'user_id'=>'required',

        ];
    }
    public function mount($task_id)
    {
        $this->task_id = $task_id;
        $this->user_id = auth()->user()->id;
      
    }
public function submitMessage(){


$data=$this->validate();

Message::create($data);
$message =Message::create($data);

broadcast(new MessageSent($message))->toOthers();

//$this->dispatch('userMessageReceived');
//$this->reset(['content']); 
}
    public function render()
    {
        return view('livewire.create-user');
    }
}
