<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Application;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function createAssignment(Request $request, $applicationId)
    {
        // Get the application using $applicationId
        $application = Application::find($applicationId);

        if (!$application) {
            // Handle the case where the application is not found
            return redirect()->back()->with('error', 'Application not found');
        }

        $existingAssignment = Assignment::where('employee_id', $application->user_id)
            ->where('job_id', $application->job_id)
            ->first();

        if ($existingAssignment) {
            return redirect()->back()->with('error', 'This user has already been hired for this job.');
        }

        // Get the job and users involved
        $job = $application->job;
        $client = $job->user; // The user who posted the job
        $employee = $application->user; // The user who applied for the job

        $currentAssignments = Assignment::where('job_id', $job->id)->count();
        $hiringCapacity = $job->hiring_capacity;

        if ($currentAssignments >= $hiringCapacity) {
            // There is no vacancy, so abort the assignment process
            return redirect()->back()->with('error', 'There are no vacancies for this job');
        }

        // Here, you can perform additional checks, validations, and create the assignment record in your database
        // You can also update the application status or any other relevant actions

        // For example, to create a new assignment record:
        Assignment::create([
            'job_id' => $job->id,
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'application_id' => $application->id,
            'assignment_status' => 'active',
        ]);

        $application->status = 'Accepted';
        $application->save();
        // Redirect to a success page or wherever you want
        return redirect()->back()->with('success', 'User hired successfully');
    }
}
