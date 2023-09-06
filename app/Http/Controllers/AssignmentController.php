<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Application;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function createAssignment(Request $request, $applicationId)
    {

        $application = Application::find($applicationId);

        if (!$application) {

            return redirect()->back()->with('error', 'Application not found');
        }

        $existingAssignment = Assignment::where('employee_id', $application->user_id)
            ->where('job_id', $application->job_id)
            ->first();

        if ($existingAssignment) {
            return redirect()->back()->with('error', 'This user has already been hired for this job.');
        }


        $job = $application->job;
        $client = $job->user;
        $employee = $application->user;

        $currentAssignments = Assignment::where('job_id', $job->id)->count();
        $hiringCapacity = $job->hiring_capacity;

        if ($currentAssignments >= $hiringCapacity) {

            return redirect()->back()->with('error', 'There are no vacancies for this job');
        }





        Assignment::create([
            'job_id' => $job->id,
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'application_id' => $application->id,
            'assignment_status' => 'active',
        ]);

        $application->status = 'Accepted';
        $application->save();

        return redirect()->back()->with('success', 'User hired successfully');
    }

    public function cencelAssignment(Request $request, $assignmentId)
    {

        $assignment = Assignment::find($assignmentId);
        $application = Application::find($assignment->application_id);

        if (!$assignment) {

            return redirect()->back()->with('error', 'Assignment not found');
        }





        $application->status = 'Rejected';
        $application->save();
        $assignment->assignment_status = 'cancelled';
        $assignment->save();


        return redirect()->back()->with('success', 'Assignment cancelled successfully');
    }

    public function deleteAssignment(Request $request, $assignmentId)
    {

        $assignment = Assignment::find($assignmentId);
        $application = Application::find($assignment->application_id);

        if (!$assignment) {

            return redirect()->back()->with('error', 'Assignment not found');
        }





        $application->status = 'Rejected';
        $application->save();
        $assignment->delete();


        return redirect()->back()->with('success', 'Assignment deleted successfully');
    }
}
