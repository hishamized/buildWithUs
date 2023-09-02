<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



class ClientController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $jobs = $user->jobs;
            $assignments = Assignment::where('client_id', auth()->user()->id)->get();

            return view('client', compact('jobs', 'assignments'));
        }

        return redirect()->route('login');
    }

    public function postJob(Request $request)
    {

        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_requirements' => 'required|string',
            'hiring_capacity' => 'required|integer',
            'site_address' => 'required|string',
            'street_address' => 'required|string', // Validate street_address
            'city' => 'required|string', // Validate city
            'state' => 'required|string', // Validate state
            'country' => 'required|string', // Validate country
            'pin_code' => 'required|string|max:10', // Validate pin_code
            'job_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'expiration_date' => 'required|date',
            'skill_set' => 'required|string',
            'site_pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'wage' => 'required|numeric',
            'currency' => 'required|string',
        ]);


        $siteAddress = [
            'street_address' => $request->input('street_address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country'),
            'pin_code' => $request->input('pin_code'),
        ];

        // Convert the address to JSON
        $siteAddressJson = json_encode($siteAddress);


        $sitePictures = [];

        if ($request->hasFile('site_pictures')) {
            foreach ($request->file('site_pictures') as $picture) {
                $path = $picture->store('site_pictures', 'public');
                $sitePictures[] = $path;
            }
        }


        // Create a new job record in the database using the Job model
        $job = new Job();
        $job->job_title = $validatedData['job_title'];
        $job->job_description = $validatedData['job_description'];
        $job->job_requirements = $validatedData['job_requirements'];
        $job->hiring_capacity = $validatedData['hiring_capacity'];
        $job->job_type = $validatedData['job_type'];
        $job->start_date = $validatedData['start_date'];
        $job->end_date = $validatedData['end_date'];
        $job->expiration_date = $validatedData['expiration_date'];
        $job->skill_set = json_encode(explode(', ', $validatedData['skill_set']));
        $job->site_pictures = json_encode($sitePictures);
        $job->wage = $validatedData['wage'];
        $job->site_address = $siteAddressJson;

        $job->currency = $validatedData['currency'];

        // Associate the job with the currently logged-in client (employer)
        $job->client_id = auth()->id();

        if ($job->save()) {
            // Redirect with a success message
            return redirect()->route('client_mode')->with('success', 'Job posted successfully.');
        } else {
            return redirect()->route('client_mode')->with('error', 'Job could not be posted.');
        }
    }

    public function viewJobDetails($id)
    {
        // Fetch the job details based on the $id parameter
        $job = Job::find($id);

        // Fetch job applications related to the job
        $jobApplications = Application::where('job_id', $id)->get();

        return view('job-details', compact('job', 'jobApplications'));
    }



    public function updateJob(Request $request, Job $job)
    {
        // Validate and store updated job data
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_requirements' => 'required|string',
            'hiring_capacity' => 'required|integer',
            'job_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'expiration_date' => 'required|date',
            'skill_set' => 'required|string',
            'site_pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'wage' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        // Handle deletion of existing images if the checkbox is checked
        if ($request->has('delete_existing_images')) {
            // Delete existing images from the storage directory
            if ($job->site_pictures) {
                foreach (json_decode($job->site_pictures) as $imageFilename) {
                    Storage::delete('public/' . $imageFilename);
                }
            }

            // Clear the site_pictures attribute in the database
            $job->site_pictures = null;
        }

        // Handle site pictures update (add new images)
        if ($request->hasFile('site_pictures')) {
            $images = is_array(json_decode($job->site_pictures, true)) ? json_decode($job->site_pictures, true) : [];

            foreach ($request->file('site_pictures') as $image) {
                // Store the image in the storage directory
                $path = $image->store('public/site_pictures');

                // Get the filename from the path
                $filename = basename($path);

                // Add the filename with the 'public/site_pictures/' prefix to the job's site_pictures JSON array
                $images[] = 'site_pictures/' . $filename;
            }

            // Update the job's site_pictures JSON array
            $job->site_pictures = json_encode($images);
        }
        // Update the skill set
        $job->skill_set = json_encode(explode(', ', $validatedData['skill_set']));

        // Save the job record
        $job->save();

        // Redirect or return a response
        return redirect()->route('job-details', ['job' => $job->id])->with('success', 'Job updated successfully.');
    }


public function deleteJob(Request $request, $jobId)
{
    $user = auth()->user();
    $job = Job::find($jobId);
    $validPassword = false;

    // Check if the entered password matches the user's hashed password
    if (!Hash::check($request->input('password'), $user->password)) {
        return redirect()->back()->with('error', 'Invalid password. Job not deleted.');
    }else{
        $validPassword = true;
    }
    if ($validPassword === false) {
        return redirect()->back()->with('error', 'Invalid password. Job not deleted.');
    } else {
       // Delete the job
       if ($job->site_pictures) {
        foreach (json_decode($job->site_pictures) as $imageFilename) {
            Storage::delete('public/' . $imageFilename);
        }
    }
    Job::destroy($jobId);

    return redirect()->route('employee_mode')->with('success', 'Your job post was deleted successfully.');

    }

}

}
