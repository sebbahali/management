<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Temporary;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function save(Request $Request ){

        if ($Request->hasFile('avatars')){

            $file=$Request->file('avatars');
            $filename=$file->getClientOriginalName();
            $folder=uniqid().'-'.now()->timestamp;
            $file->storeAs('avatars/'.$folder,$filename,'public');

        Temporary::create([
        
        'folder'=>$folder   ,
        'filename'=>$filename
        ]);
        
            return $folder;
        }
        return '';
            }
}
