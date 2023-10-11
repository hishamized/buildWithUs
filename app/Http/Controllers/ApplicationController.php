<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use App\Models\Profile;
use App\Models\Job;

class ApplicationController extends Controller
{


    public function viewApplications($jobId)
    {

    }

    public function acceptApplication($userId, $jobId)
{

    $job = Job::find($jobId);
    $client = User::find($job->client_id);

    if($client->id == auth()->user()->id){
        return redirect()->back()->with('error', 'You cannot apply for your own job');
    }

    $existingApplication = Application::where('user_id', $userId)
        ->where('job_id', $jobId)
        ->first();

    if ($existingApplication) {

        return redirect()->back()->with('error', 'You have already applied for this job');
    }


    Application::create([
        'user_id' => $userId,
        'job_id' => $jobId,
        'status' => 'pending',

    ]);

    $job->increment('application_count');


    return redirect()->route('jobFullView', ['id' => $jobId, ''])->with('success', 'Application accepted successfully');
}


public function cancelApplication(Request $request)
{

    $userId = $request->user_id;
    $jobId = $request->job_id;

    $job = Job::find($jobId);


    $application = Application::where('user_id', $userId)
        ->where('job_id', $jobId)
        ->first();

    if ($application) {

        $application->delete();

        $job->decrement('application_count');

        return redirect()->back()->with('success', 'Application canceled successfully');
    }

    return redirect()->back()->with('error', 'Application not found');
}




    public function rejectApplication($applicationId)
    {

    }


}
