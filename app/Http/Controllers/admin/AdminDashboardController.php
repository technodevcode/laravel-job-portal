<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminDashboardController extends Controller
{
    use SoftDeletes;

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

    public function jobsList(Request $request)
{
        $status = $request->get('status', 'active');

        if ($status === 'deleted') {
            $jobs = Job::onlyTrashed()->orderBy('created_at', 'asc')->with(['user', 'applications'])->paginate(10);
        } else {
            $jobs = Job::where('status', 1)->orderBy('created_at', 'asc')->with(['user', 'applications'])->paginate(10);
        }

        return view('admin.jobs.list', compact('jobs', 'status'));
    }


    public function editJob(Request $request, $id){
        
        $categories = Category::orderBy('name', 'ASC')->where('status', '1')->get();
        $job_types = JobType::orderBy('name', 'ASC')->where('status', '1')->get();
        $experienceOptions = config('experience.options');

        $job = job::where([
            'id'  => $id
        ])->first(); 

        if(empty($job)){
            abort(404);
        }

        return view('admin.jobs.edit', compact('categories', 'job_types', 'experienceOptions', 'job'));
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
            
            $job->status = $request->status;
            $job->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;
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

    public function destroyJob(Request $request) {
        $job = Job::where([
            'id' => $request->id
        ])->first();


        if ($job == null) {
            session()->flash('error','Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        $job->status = 0;
        $job->save();
        $job->delete();

        //Job::where('id',$request->id)->delete();
        
        session()->flash('success','Job deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function restoreJob($id)
    {
        $job = Job::onlyTrashed()->findOrFail($id);
        $job->status = 1;
        $job->save();
        $job->restore();
        return redirect()->route('admin.jobs', ['status' => 'deleted'])->with('success', 'Job restored successfully.');
    }

    public function jobApplications(){
        $applications = JobApplication::with(['job', 'user', 'employer'])->orderBy('created_at', 'DESC')->paginate(10);       
        return view('admin.job-applications.list', compact('applications'));
    }
}
