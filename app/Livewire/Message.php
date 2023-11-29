<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use App\Models\Message as msg;

class Message extends Component
{
    public $task_id;
    public $msgs = [];
    public $task;
  //  protected $listeners = ['messageReceived' => 'updateMessages'];

  protected $listeners = ['messageReceived'];

  public function mount($task_id)
    {
        $this->task_id = $task_id;

        //$this->updateMessages(); 
    }
   /* public function updateMessages()
    {
     
      $this->msgs=msg::where('task_id', $this->task_id)->with('task')->get();




    }*/
   
    public function render()
    {
        return view('livewire.message');
    }
    public function messageReceived($msgs)
    {
        // Update the messages array with the new message
        $this->msgs = $msgs;
        dd($this->msgs);
    }
}
