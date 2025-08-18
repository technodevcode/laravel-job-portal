<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AdminDashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }

    public function usersList(){
        $users = User::orderBy('created_at', 'asc')->paginate(10);
        return view('admin.users.list', compact('users'));
    }

    public function editUser(Request $request, $id){
        
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id){
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id', 
        ]);

        if($validator->passes()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

            session()->flash('success', 'User information updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } 
    }

    public function destroyUser(Request $request){
        $id = $request->id;

        $user = User::find($id);

        if ($user == null) {
            session()->flash('error','User not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success','User deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
