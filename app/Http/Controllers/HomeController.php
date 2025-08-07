<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //this method show our home page
    public function index(){
        $categories = Category::where('status', '1')->take('8')->get();
        $featuredJobs = job::where('status', '1')->with('JobType')->where('isFeatured', 1)->take('6')->get();
        $latestJobs = job::where('status', '1')->with('JobType')->orderBy('created_at', 'DESC')->with('JobType')->take('6')->get();
        return view('front.home', compact('categories', 'featuredJobs', 'latestJobs'));
    }
}
