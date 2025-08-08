<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;

class JobsController extends Controller
{

    //this method show job detial page
    public function index(Request $request) {
        $categories = Category::where('status',1)->get();
        $job_types = JobType::where('status',1)->get();
        $experienceOptions = config('experience.options');

        $jobs = Job::where('status',1);

        // Search using keyword
        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function($query) use ($request) {
                $query->orWhere('job_title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');
            });
        }

        //search using location 
        if(!empty($request->location)){
            $jobs = $jobs->where('location', $request->location);
        }

        //search using category 
        if(!empty($request->category)){
            $jobs = $jobs->where('category_id', $request->category);
        }

        //search using Job type 
        $job_type_array = [];
        if(!empty($request->job_type)){
            $job_type_array = explode(',', $request->job_type);
            $jobs = $jobs->whereIn('job_type_id', $job_type_array);
        }

        //search using experience 
        if(!empty($request->experience)){
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with(['jobType','category']);
        
        if($request->sort == '0') {
            $jobs = $jobs->orderBy('created_at','ASC');
        } else {
            $jobs = $jobs->orderBy('created_at','DESC');
        }

        $jobs = $jobs->paginate(9);

        return view('front.jobs', compact('categories', 'job_types', 'experienceOptions', 'jobs', 'job_type_array')); 
    }
}
