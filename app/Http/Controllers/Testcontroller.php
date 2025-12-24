<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Testcontroller extends Controller
{
    // public function getusers($id)
    // {
    //     $users = User::where('id', $id)->get();
    // return $users;
    // }
    // public function countusers()
    // {
    //     $count = User::count();
    //     return $count;
    // }
    public function getpage()
     {
        return view('test');

}
public function formdata(request $testdata)
{
return $testdata;
}
}
