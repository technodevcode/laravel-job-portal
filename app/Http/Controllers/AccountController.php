<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if($validator->passes()){    
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile-pic/'), $imageName);

            //create a small thumbnail
            $srcPath = public_path('/profile-pic/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($srcPath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile-pic/thumb/'.$imageName));
            
            //Delete old profile pics
            File::delete(public_path('/profile-pic/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile-pic/'.Auth::user()->image));

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
    
    public function createJob(){
        $categories = Category::orderBy('name')->where('status', '1')->get();
        $job_types = JobType::orderBy('name')->where('status', '1')->get();
        $experienceOptions = config('experience.options');
        return view('front.account.job.create', compact('categories', 'job_types', 'experienceOptions'));
    }

    public function saveJob(Request $request){

        $rules = [
            'title' =>  'required|min:5|max:200',
            'category' =>  'required',
            'job_types' =>  'required',
            'vacancy' =>  'required|integer',
            'location' =>  'required|max:50',
            'description' =>  'required',
            'company_name' =>  'required|min:3|max:75',
            'experience' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){

            $job = new Job();
            $job->job_title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_types;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;       
            $job->save();

            session()->flash('success', 'Job created successfully.');

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

    public function myJobs(){
        $jobs = job::where('user_id',Auth::user()->id)->with('JobType')->orderBy('created_at', 'DESC')->paginate(10);      
        return view('front.account.job.my-jobs', ['jobs' => $jobs]);
    }

    public function editJob(Request $request, $id){
        $categories = Category::orderBy('name', 'ASC')->where('status', '1')->get();
        $job_types = JobType::orderBy('name', 'ASC')->where('status', '1')->get();
        $experienceOptions = config('experience.options');

        $job = job::where([
            'user_id' => Auth::user()->id,
            'id'  => $id
        ])->first(); 

        if(empty($job)){
            abort(404);
        }

        return view('front.account.job.edit', compact('categories', 'job_types', 'experienceOptions', 'job'));
    }

    public function updateJob(Request $request, $id){

        $rules = [
            'title' =>  'required|min:5|max:200',
            'category' =>  'required',
            'job_types' =>  'required',
            'vacancy' =>  'required|integer',
            'location' =>  'required|max:50',
            'description' =>  'required',
            'company_name' =>  'required|min:3|max:75',
            'experience' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){

            $job = Job::find($id);
            $job->job_title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_types;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;    
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;       
            $job->save();

            session()->flash('success', 'Job updated successfully.');

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

    public function deleteJob(Request $request) {

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();


        if ($job == null) {
            session()->flash('error','Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id',$request->jobId)->delete();
        
        session()->flash('success','Job deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function myJobApplications(){
        $appliedJobs = JobApplication::where('user_id',Auth::user()->id)->with(['job', 'job.jobType', 'job.category', 'job.applications'])->orderBy('created_at', 'DESC')->paginate(10);       
       
        return view('front.account.job.my-job-applications', compact('appliedJobs'));
    }

    public function appliedJobDelete(Request $request) {

        $job = JobApplication::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();
    
    
        if ($job == null) {
            session()->flash('error','Either job removed or not found.');
            return response()->json([
                'status' => true
            ]);
        }
    
        JobApplication::where('id',$request->jobId)->delete();
        
        session()->flash('success','Job removed successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function savedJobs(){

        $savedJobs = SavedJob::where([
            'user_id' => Auth::user()->id
        ])->with(['job','job.jobType','job.applications'])
        ->orderBy('created_at','DESC')
        ->paginate(10);

        return view('front.account.job.saved-jobs',[
            'savedJobs' => $savedJobs
        ]);
    }

    public function removeSavedJob(Request $request){
        $savedJob = SavedJob::where([
                                    'id' => $request->id, 
                                    'user_id' => Auth::user()->id]
                                )->first();
        
        if ($savedJob == null) {
            session()->flash('error','Job not found');
            return response()->json([
                'status' => false,                
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success','Job removed successfully.');

        return response()->json([
            'status' => true,                
        ]);
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5', 
            'confirm_password' => 'required|min:5|same:new_password', 
        ]);

        if($validator->passes()){

            if(Hash::check($request->old_password, Auth::user()->password) == false){
                session()->flash('error', 'You old password is incorrect.');
                return response()->json([
                    'status' => true,                    
                ]);
            }

            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->new_password); 
            $user->save();   

            session()->flash('success', 'Password updated successfully.');

            return response()->json([
                'status' => true,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
