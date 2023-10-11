<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $jobs = Job::all();
            $profile = $user->profile;
            $applications = Application::where('user_id', $user->id)->get();
            $assignments = Assignment::where('employee_id', auth()->user()->id)->get();
            return view('employee', compact('user', 'jobs', 'profile', 'applications', 'assignments'));
        }
    }

    public function search(Request $request)
    {
        $searchText = trim($request->searchText);

        $matchingJobs = Job::with('user')->get();

        $html = '';

        if (!empty($matchingJobs)) {
            foreach ($matchingJobs as $job) {
                $jobSkills = json_decode($job->skill_set);

                if (in_array($searchText, $jobSkills)) {
                    $html .= '<li class="list-group-item">';
                    $html .= '<div><strong>' . "Job Title : </strong>" . $job->job_title . '</div>';
                    $html .= '<div><strong>' . "Posted On : </strong>" . $job->created_at->format('Y-m-d') . '</div>';
                    $html .= '<div><strong>Client Name: </strong>' . $job->user->name . '</div>';
                    $html .= '<div><span>' . 'Views : ' . $job->views . "</span></div>";
                    $html .= '<div><span>' . 'Applications : ' . $job->application_count . "</span></div>";
                    $html .= '<div><a href="' . route('jobFullView', ['id' => $job->id]) . '">' . 'View Full Job : ' . "</a></div>";
                    $html .= '</li>';
                    $html .= "<hr>";
                }
            }
        } else {
            $html = 'No records found';
        }

        return response()->json(['html' => $html]);
    }

    public function jobFullView($id)
    {


        $userHasApplied = Application::where('user_id', Auth::id())
            ->where('job_id', $id)
            ->exists();
        $job = Job::findOrFail($id);


        if (!$job) {
            abort(404);
        }

        $job->increment('views');


        return view('job-full-view', compact('job', 'userHasApplied'));
    }
}
