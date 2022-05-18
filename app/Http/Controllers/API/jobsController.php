<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\department;
use App\Models\location;
use App\Models\position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\client;
use App\Models\jobs;
use App\Models\skills;
use App\Models\collaborator_members;
use Illuminate\Support\Facades\Auth;
use App\Models\selected_job_skills;
use App\Models\selected_collaborator_members;
use App\Models\JobStatusComments;


class jobsController extends Controller
{
    private $status_code = 200;

    public function ShowAddJobForm($id)
    {


        $all_job_categories = category::where('category_status', 1)->where('business_id', $id)->get();
        //$teams = Role ::all();
        $all_job_departments = department::where('department_status', 1)->where('business_id', $id)->get();
        //$team_members = client::where('client_status',1)->where('business_id',$id)->where('isConfirmed',1)->get();
        $all_job_positions = position::where('position_status', 1)->where('business_id', $id)->get();
        $location = location::where('location_status', 1)->where('business_id', $id)->get();
        $contract_type = array(
            1 => 'Full Time Hire',
            2 => 'Contract to Hire',
            3 => 'Contract (Third Party)',
            4 => 'SOW Consultant',
            5 => 'Freelance Consultant'
        );
        $project_sow = array(
            1 => 'Dummy Value-Needed to discuss'
        );
        $schedule_type = array(
            1 => 'Dummy Schedule-type
'
        );
        $pay_type = array(
            1 => 'Per Hour',
            2 => 'Per Day',
            3 => 'Per Week',
            4 => 'Annual Salary'
        );
        $hiring_manager = client::where('client_status', 1)->where('business_id', $id)->where('isConfirmed', 1)->with("roles")->whereHas("roles", function ($q) {
            $q->whereIn("id", [3]);
        })->get();
        $Secondary_hiring_manager = client::where('client_status', 1)->where('business_id', $id)->where('isConfirmed', 1)->with("roles")->whereHas("roles", function ($q) {
            $q->whereIn("id", [3]);
        })->get();
        // $other_team_members = client::where('client_status', 1)->where('business_id', $id)->where('isConfirmed', 1)->get();
        $status = array(
            1 => 'Active Job',
            2 => 'Inactive Job',
            3 => 'Draft Job',
            4 => 'Deleted Job',
            5 => 'Achieve Job'
        );
         $other_team_members = array(
            1 => 'Client',
            2 => 'Program',
            3 => 'Product Administrator',

        );
        $skills = skills::where('skill_status', 1)->where('business_id', $id)->get();
        $collaborator_members = collaborator_members::where('collaborator_member_status', 1)->where('business_id', $id)->get();
        return response()->json([
            "status" => $this->status_code,
            "success" => true,
            'all_job_categories' => $all_job_categories,
            'all_job_departments' => $all_job_departments,
            'all_job_positions' => $all_job_positions,
            'location' => $location,
            'contract_type' => $contract_type,
            'project_sow' => $project_sow,
            'schedule_type' => $schedule_type,
            'pay_type' => $pay_type,
            'hiring_manager' => $hiring_manager,
            'secondary_hiring_manager' => $Secondary_hiring_manager,
            'other_team_members' => $other_team_members,
            'Status' => $status,
            "message" => "Get jobs data.",
            "skills" => $skills,
            "collaborator_members" => $collaborator_members
        ]);
    }

    public function AddJobDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "business_id" => "required",
            "job_title" => "required",
            "job_category" => "required",
            "job_department" => "required",
            //"schedule_type" => "required",
            "job_position" => "required",
            //"job_openings" => "required",
            "job_location" => "required",
            "job_remote" => "required",
            "job_contract_type" => "required",
            "job_project_sow" => "required",
            "job_pay_type" => "required",
            "job_annual_bill_rate" => "required",
            "job_min_bill_rate" => "required",
            "job_max_bill_rate" => "required",
            //"job_annual_pay_rate" => "required",
            //"job_min_pay_rate" => "required",
            //"job_annual_may_rate" => "required",
            //"job_start_date" => "required",
            //"job_end_date" => "required",
            //"job_hire_date" => "required",
            //"job_exclude_holidays" => "required",
            //"job_working_days" => "required",
            // "job_tentative_end_date" => "required",
            "job_description" => "required",
            //"job_description_1" => "required",
            //"job_description_2" => "required",
            "job_internal_notes" => "required",
            "job_career_web_internal" => "required",
            "job_career_web_external" => "required",
            "hiring_manager" => "required",
            "hiring_manager_1" => "required",
            "job_status" => "required",
            //"job_created_by" => "required",
            "job_portal_user" => "required",
            //"job_date_created" => "required",
            //"job_date_published" => "required",
            //"job_date_updated" => "required",
            //"tax_labels" => "required.*",
            //"tax_labels" => "required",
            "skills" => "required.*",
            "skills" => "required",
            "collaborators" => "required.*",
            "collaborators" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $jobDataArray = array(
            "business_id" => $request->business_id,
            "job_title" => $request->job_title,
            "job_category" => $request->job_category,
            "job_department" => $request->job_department,
            "job_position" => $request->job_position,
            //"job_openings" => $request->job_openings,
            "job_location" => $request->job_location,
            "job_remote" => $request->job_remote,
            "job_contract_type" => $request->job_contract_type,
            "job_project_sow" => $request->job_project_sow,
            "job_pay_type" => $request->job_pay_type,
            "job_annual_bill_rate" => $request->job_annual_bill_rate,
            "job_min_bill_rate" => $request->job_min_bill_rate,
            "job_max_bill_rate" => $request->job_max_bill_rate,
            //"job_annual_pay_rate" => 'discuss',
            //"job_min_pay_rate" => 'discuss',
            //"job_annual_may_rate" => 'discuss',
            //"job_start_date" => date('Y-m-d H:i:s'),
            //"job_end_date" => date('Y-m-d H:i:s'),
            //"job_hire_date" => date('Y-m-d H:i:s'),
            //"job_exclude_holidays" => 'discuss',
            //"job_working_days" =>  'discuss',
            //"job_tentative_end_date" =>  'discuss',
            "job_description" => $request->job_description,
            //"job_description_1" => $request->job_description_1,
            //"job_description_2" => $request->job_description_2,
            "job_internal_notes" => $request->job_internal_notes,
            "job_career_web_internal" => $request->job_career_web_internal,
            "job_career_web_external" => $request->job_career_web_external,
            "hiring_manager" => $request->hiring_manager,
            "hiring_manager_1" => $request->hiring_manager_1,
            "job_status" => $request->job_status,
            "job_created_by" => $id = Auth::user()->id,
            "job_portal_user" => $request->job_portal_user,
            "job_date_created" => date('Y-m-d H:i:s'),
            "job_date_published" => date('Y-m-d H:i:s'),
            "job_date_updated" => date('Y-m-d H:i:s'),
        );
        $jobs = jobs::create($jobDataArray);
        if ($jobs) {
            //[{"skill_name":"programingEEEE"},{"skill_name":"data entry"},{"skill_name":"project management"}]
            //[{"collaborator_name":"oneEEEE"},{"collaborator_name":"two"},{"collaborator_name":"three"}]
            $skills = json_decode($request->skills, true);
            $skill_names = [];
            foreach ($skills as $skill) {
                //dd($skill);
                if (skills::where('name', '=', $skill['value'])->count() < 1) {
                    $skillsDataArray = array(
                        "business_id" => $request->business_id,
                        "name" => $skill['value'],
                        "skill_status" => 1
                    );
                    $insert_skills = skills::create($skillsDataArray);
                }
                $skill_names[] = $skill['value'];
            }
            $job_skills = skills::where('skill_status', 1)->where('business_id', $request->business_id)->whereIn('name', $skills)->get();
            if (!empty($job_skills)) {
                foreach ($job_skills as $job_skill) {
                    $skillsSelectedDataArray = array(
                        "job_id" => $jobs->id,
                        "skill_id" => $job_skill->id
                    );
                    $insertSelectedDataArray = selected_job_skills::create($skillsSelectedDataArray);
                }
            }
            $collaborators = json_decode($request->collaborators, true);
            $collaborator_names = [];
            foreach ($collaborators as $collaborator) {
                if (collaborator_members::where('name', '=', $collaborator['value'])->count() < 1) {
                    $collaboratorsDataArray = array(
                        "business_id" => $request->business_id,
                        "name" => $collaborator['value'],
                        "collaborator_member_status" => 1
                    );
                    $insert_collaborators = collaborator_members::create($collaboratorsDataArray);

                }
                $collaborator_names[] = $collaborator['value'];
            }
            $job_collaborator_names = collaborator_members::where('collaborator_member_status', 1)->where('business_id', $request->business_id)->whereIn('name', $collaborator_names)->get();
            if (!empty($job_collaborator_names)) {
                foreach ($job_collaborator_names as $job_collaborator_name) {
                    $collaboratorSelectedDataArray = array(
                        "job_id" => $jobs->id,
                        "collaborator_members_id" => $job_collaborator_name->id
                    );
                    $insertCollaboratorSelectedDataArray = selected_collaborator_members::create($collaboratorSelectedDataArray);
                }
            }
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "jobs_data added successfully.", "jobs_data" => $jobs]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add jobs data"]);
        }
    }

    public function EditJobDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "job_id" => "required",
            "business_id" => "required",
            "job_title" => "required",
            "job_category" => "required",
            "job_department" => "required",
            //"schedule_type" => "required",
            "job_position" => "required",
            //"job_openings" => "required",
            "job_location" => "required",
            "job_remote" => "required",
            "job_contract_type" => "required",
            "job_project_sow" => "required",
            "job_pay_type" => "required",
            "job_annual_bill_rate" => "required",
            "job_min_bill_rate" => "required",
            "job_max_bill_rate" => "required",
            //"job_annual_pay_rate" => "required",
            //"job_min_pay_rate" => "required",
            //"job_annual_may_rate" => "required",
            //"job_start_date" => "required",
            //"job_end_date" => "required",
            //"job_hire_date" => "required",
            //"job_exclude_holidays" => "required",
            //"job_working_days" => "required",
            // "job_tentative_end_date" => "required",
            "job_description" => "required",
            //"job_description_1" => "required",
            //"job_description_2" => "required",
            "job_internal_notes" => "required",
            "job_career_web_internal" => "required",
            "job_career_web_external" => "required",
            "hiring_manager" => "required",
            "hiring_manager_1" => "required",
            "job_status" => "required",
            //"job_created_by" => "required",
            "job_portal_user" => "required",
            //"job_date_created" => "required",
            //"job_date_published" => "required",
            //"job_date_updated" => "required",
            //"tax_labels" => "required.*",
            //"tax_labels" => "required",
            "skills" => "required.*",
            "skills" => "required",
            "collaborators" => "required.*",
            "collaborators" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $jobDataArray = array(
            "business_id" => $request->business_id,
            "job_title" => $request->job_title,
            "job_category" => $request->job_category,
            "job_department" => $request->job_department,
            "job_position" => $request->job_position,
            //"job_openings" => $request->job_openings,
            "job_location" => $request->job_location,
            "job_remote" => $request->job_remote,
            "job_contract_type" => $request->job_contract_type,
            "job_project_sow" => $request->job_project_sow,
            "job_pay_type" => $request->job_pay_type,
            "job_annual_bill_rate" => $request->job_annual_bill_rate,
            "job_min_bill_rate" => $request->job_min_bill_rate,
            "job_max_bill_rate" => $request->job_max_bill_rate,
            //"job_annual_pay_rate" => 'discuss',
            //"job_min_pay_rate" => 'discuss',
            //"job_annual_may_rate" => 'discuss',
            //"job_start_date" => date('Y-m-d H:i:s'),
            //"job_end_date" => date('Y-m-d H:i:s'),
            //"job_hire_date" => date('Y-m-d H:i:s'),
            //"job_exclude_holidays" => 'discuss',
            //"job_working_days" =>  'discuss',
            //"job_tentative_end_date" =>  'discuss',
            "job_description" => $request->job_description,
            //"job_description_1" => $request->job_description_1,
            //"job_description_2" => $request->job_description_2,
            "job_internal_notes" => $request->job_internal_notes,
            "job_career_web_internal" => $request->job_career_web_internal,
            "job_career_web_external" => $request->job_career_web_external,
            "hiring_manager" => $request->hiring_manager,
            "hiring_manager_1" => $request->hiring_manager_1,
            "job_status" => $request->job_status,
            "job_created_by" => $id = Auth::user()->id,
            "job_portal_user" => $request->job_portal_user,
            "job_date_created" => date('Y-m-d H:i:s'),
            "job_date_published" => date('Y-m-d H:i:s'),
            "job_date_updated" => date('Y-m-d H:i:s'),
        );
        $jobs = jobs::where('id', $request->job_id)->update($jobDataArray);
        if ($jobs) {
            //[{"skill_name":"programingEEEE"},{"skill_name":"data entry"},{"skill_name":"project management"}]
            //[{"collaborator_name":"oneEEEE"},{"collaborator_name":"two"},{"collaborator_name":"three"}]
            $skills = json_decode($request->skills, true);
            $skill_names = [];
            foreach ($skills as $skill) {
                //dd($skill);
                if (skills::where('name', '=', $skill['value'])->count() < 1) {
                    $skillsDataArray = array(
                        "business_id" => $request->business_id,
                        "name" => $skill['value'],
                        "skill_status" => 1
                    );
                    $insert_skills = skills::create($skillsDataArray);
                }
                $skill_names[] = $skill['value'];
            }
            $delete_old_skills = selected_job_skills::where('job_id', $request->job_id)->delete();
            $job_skills = skills::where('skill_status', 1)->where('business_id', $request->business_id)->whereIn('name', $skills)->get();
            if (!empty($job_skills)) {
                foreach ($job_skills as $job_skill) {
                    $skillsSelectedDataArray = array(
                        "job_id" => $request->job_id,
                        "skill_id" => $job_skill->id
                    );
                        $insertSelectedDataArray = selected_job_skills::create($skillsSelectedDataArray);

                }
            }
            $collaborators = json_decode($request->collaborators, true);
            $collaborator_names = [];
            foreach ($collaborators as $collaborator) {
                if (collaborator_members::where('name', '=', $collaborator['value'])->count() < 1) {
                    $collaboratorsDataArray = array(
                        "business_id" => $request->business_id,
                        "name" => $collaborator['value'],
                        "collaborator_member_status" => 1
                    );
                    $insert_collaborators = collaborator_members::create($collaboratorsDataArray);
                }
                $collaborator_names[] = $collaborator['value'];
            }
            $delete_old_collaborator = selected_collaborator_members::where('job_id', $request->job_id)->delete();
            $job_collaborator_names = collaborator_members::where('collaborator_member_status', 1)->where('business_id', $request->business_id)->whereIn('name', $collaborator_names)->get();
            if (!empty($job_collaborator_names)) {
                foreach ($job_collaborator_names as $job_collaborator_name) {
                    $collaboratorSelectedDataArray = array(
                        "job_id" => $request->job_id,
                        "collaborator_members_id" => $job_collaborator_name->id
                    );
                        $insertCollaboratorSelectedDataArray = selected_collaborator_members::create($collaboratorSelectedDataArray);

                }
            }
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "jobs_data updated successfully.", "jobs_data" => $jobs]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add jobs data"]);
        }
    }


    public function ListAllJobs($id)
    {
        $jobs = jobs::with('locations')->with('jobs_skills')->with('jobs_collaborators')->where('business_id', $id)->get();
        /*foreach ($jobs as $job){
            foreach ($job->jobs_skills as $skill){
                echo $skill->name;
                echo "</br>";
            }
        }*/
        /*foreach ($jobs as $job){
            foreach ($job->jobs_collaborators as $jobs_collaborators){
                echo $jobs_collaborators->name;
                echo "</br>";
            }
        }*/
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get jobs data.", "location" => $jobs]);
    }
    public function GetJobDetail($id)
    {
          $contract_type = array(
            1 => 'Full Time Hire',
            2 => 'Contract to Hire',
            3 => 'Contract (Third Party)',
            4 => 'SOW Consultant',
            5 => 'Freelance Consultant'
        );
        $project_sow = array(
            1 => 'Dummy Value-Needed to discuss'
        );
        $schedule_type = array(
            1 => 'Dummy Schedule-type
'
        );
        $pay_type = array(
            1 => 'Per Hour',
            2 => 'Per Day',
            3 => 'Per Week',
            4 => 'Annual Salary'
        );
        $status = array(
            1 => 'Active Job',
            2 => 'Inactive Job',
            3 => 'Draft Job',
            4 => 'Deleted Job',
            5 => 'Achieve Job'
        );
        $status_change_reason = array(
            1 => 'Need to Active Job',
            2 => 'Need to Inactive Job',
            3 => 'Need to Draft Job',
            4 => 'Need to Deleted Job',
            5 => 'Need to Achieve Job'
        );
        $jobs = jobs::with('job_position')->with('locations')->with('jobs_skills')->with('jobs_collaborators')->with('job_categories')->with('job_department')->with('hiring_manager')->with('secondary_hiring_manager')->where('id', $id)->first();

        return response()->json(["statuss" => $this->status_code, "success" => true, "message" => "Get jobs data.", "job_details" => $jobs,'contract_type' => $contract_type,
            'project_sow' => $project_sow,
            'schedule_type' => $schedule_type,
            'pay_type' => $pay_type,
            'status' => $status,
            'status_change_reason' => $status_change_reason,
            'profile_image_path' => storage_path('app/public/uploads/images/CompanyLogo/')
            ]);
    }

    public function changeJobStatus(Request $request){
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "status_change_reason" => "required",
            "status_change_comment" => "required",
            "job_id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $PositionDataArray = array(
            "job_status" => $request->status
        );
        $job_status = jobs::where('id',$request->job_id)->update($PositionDataArray);
        if ($job_status){
            $statusCommentDataArray = array(
                "job_status" => $request->status,
                "status_change_reason" => $request->status_change_reason,
                "status_change_comment" => $request->status_change_comment
            );
            $insert_skills = JobStatusComments::create($statusCommentDataArray);
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Job status updated successfully.", "job_id" => $request->job_id]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add job status"]);
        }
    }
    public function deleteJob(Request $request){
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "job_id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $statsDataArray = array(
            "job_status" => $request->status
        );
        $job_status = jobs::where('id',$request->job_id)->update($statsDataArray);
        if ($job_status){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Job delted successfully.", "job_id" => $request->job_id]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to delete job status"]);
        }
    }

    public function archiveJob(Request $request){
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "job_id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $statsDataArray = array(
            "job_status" => $request->status
        );
        $job_status = jobs::where('id',$request->job_id)->update($statsDataArray);
        if ($job_status){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Job archived successfully.", "job_id" => $request->job_id]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to archived job status"]);
        }
    }

}
