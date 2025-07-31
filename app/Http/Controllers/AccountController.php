<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function registration(){
        return view('front.account.registration');
    }

    public function processRegistration(Request $request){  
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password', 
            'confirm_password' => 'required|min:5', 
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);           
            $user->save();

            session()->flash('success', 'You have registerd successfully.');

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

    public function login(){
        return view('front.account.login');
    }

    public function authenticate(Request $request){  
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required', 
        ]);   

        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password ])){
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error', 'Either Email/Password is incorrect');
            }
        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile(){
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('front.account.profile', ['user' => $user]);
    }

    public function updateProfile(Request $request){  
        $id = Auth::user()->id;
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

            session()->flash('success', 'Profile updated successfully.');

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

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePic(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if($validator->passes()){    
            $id = Auth::user()->id;
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile-pic/'), $imageName);

            User::where('id', $id)->update(['image' => $imageName]);

            session()->flash('success', 'Profile picture updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => $validator->errors()
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }   
}
