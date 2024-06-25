<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;    //this represnts the users table

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user=$user;
    }

    public function index(){
        #Retrieve all users
        $all_users=$this->user->withTrashed()->latest()->paginate(5); //pagination
        #Note:The "withTrashed()-will include the soft deleted users in a query redult
        return view('admin.users.index')
            ->with('all_users',$all_users);
    }

    public function deactivate($id){
        $this->user->destroy($id);  //DELETE FROM users WHERE id=$id;
        return redirect()->back();
    }

    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        #withTrashed(),trashed(),onlyTrashed()
        return redirect()->back();
    }
}