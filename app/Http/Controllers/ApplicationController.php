<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use App\Models\Profile;
use App\Models\Job;

class ApplicationController extends Controller
{

    // View all applications for a job
    public function viewApplications($jobId)
    {
        // Your code to retrieve and display applications for a specific job
    }

    public function acceptApplication($userId, $jobId)
{

    $job = Job::find($jobId);
    $client = User::find($job->client_id);

    if($client->id == auth()->user()->id){
        return redirect()->back()->with('error', 'You cannot apply for your own job');
    }
    // Check if the user has already applied for this job
    $existingApplication = Application::where('user_id', $userId)
        ->where('job_id', $jobId)
        ->first();

    if ($existingApplication) {
        // Handle the case where the user has already applied for this job
        return redirect()->back()->with('error', 'You have already applied for this job');
    }

    // Create a new application
    Application::create([
        'user_id' => $userId,
        'job_id' => $jobId,
        'status' => 'pending', // Assuming you have a 'status' field
        // Add other fields as needed
    ]);

    // Redirect to a success page or wherever you want
    return redirect()->route('jobFullView', ['id' => $jobId, ''])->with('success', 'Application accepted successfully');
}


public function cancelApplication(Request $request)
{
    // Get the user ID and job ID from the request
    $userId = $request->user_id;
    $jobId = $request->job_id;

    // Check if the application exists
    $application = Application::where('user_id', $userId)
        ->where('job_id', $jobId)
        ->first();

    if ($application) {
        // If the application exists, delete it
        $application->delete();

        return redirect()->back()->with('success', 'Application canceled successfully');
    }

    return redirect()->back()->with('error', 'Application not found');
}



    // Reject an application
    public function rejectApplication($applicationId)
    {
        // Your code to mark an application as rejected
    }

    // Additional application-related methods can be added as needed
}
