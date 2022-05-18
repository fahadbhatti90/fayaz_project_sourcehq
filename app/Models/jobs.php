<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\location;
use App\Models\skills;
use App\Models\category;
use App\Models\position;
use App\Models\department;

class jobs extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    protected $fillable = [
        "business_id",
        "job_title",
        "job_department",
        "job_category",
        "job_position",
        "job_openings",
        "job_location",
        "job_remote",
        "job_contract_type",
        "job_project_sow",
        "job_pay_type",
        "job_annual_bill_rate",
        "job_min_bill_rate",
        "job_max_bill_rate",
        "job_annual_pay_rate",
        "job_min_pay_rate",
        "job_annual_may_rate",
        "job_start_date",
        "job_end_date",
        "job_hire_date",
        "job_exclude_holidays",
        "job_working_days",
        "job_tentative_end_date",
        "job_description",
        "job_description_1",
        "job_description_2",
        "job_internal_notes",
        "job_career_web_internal",
        "job_career_web_external",
        "hiring_manager",
        "hiring_manager_1",
        "job_status",
        "job_created_by",
        "job_portal_user",
        "job_date_created",
        "job_date_published",
        "job_date_updated",
    ];
    /**
     * Get the phone record associated with the user.
     */
    public function locations()
    {
        return $this->hasOne(location::class, 'id','job_location');
    }
    public function jobs()
    {
        return $this->belongsToMany(skills::class);
    }
    public function jobs_skills(){
        return $this->belongsToMany('App\Models\skills', 'selected_job_skills', 'job_id', 'skill_id');
    }
    public function skills_names()
    {
        return $this->hasMany('App\Models\selected_job_skills', 'skill_id');
    }

    public function jobs_collaborators(){
        return $this->belongsToMany('App\Models\collaborator_members', 'selected_collaborator_members', 'job_id', 'collaborator_members_id');
    }
    public function collaborators_names()
    {
        return $this->hasMany('App\Models\selected_collaborator_members', 'collaborator_members_id');
    }
    /**
     * Get the comments for the blog post.
     */
    public function job_categories()
    {
        return $this->belongsTo(category::class,'job_category','id');
    }
    public function job_position()
    {
        return $this->belongsTo(position::class,'job_position','id');
    }
    public function job_department()
    {
        return $this->belongsTo(department::class,'job_department','id');
    }
    public function hiring_manager()
    {
        return $this->belongsTo(client::class,'hiring_manager','id');
    }
    public function secondary_hiring_manager()
    {
          return $this->belongsTo(client::class,'hiring_manager_1','id');
    }

}
