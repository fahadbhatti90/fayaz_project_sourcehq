<?php

namespace App\Http\Controllers\API;

use App\Models\client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    private $status_code = 200;
    // public $guard_name = 'api';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

        //  $this->middleware('guest')->except('index');
        //  $this->middleware('permission:role-list|role-create|role-edit|role-delete,web', ['only' => ['index','store']]);
        //  $this->middleware('permission:role-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetAllUserRoles(Request $request)
    {
        $roles = Role ::withCount('users') -> get();
        $all_roles = compact('roles');
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get all roles with user count.", "data" => $all_roles]);
    }
    public function GetRolesClients($role_id){
        
        //$role_users = client::role($role)->get();
        $role_users = Role ::with('users')->where('id', $role_id)-> get();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get all roles with users.", "data" => $role_users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    // dd($request->input('permission'));
        $role = Role::create(['name' => $request->input('name')]);
         $r =  implode(",",$request->input('permission'));


 $role->syncPermissions(explode(",",$r));


         return response()->json([
                'message'=>'Role Created Successfully!!'
            ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
     return   $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        // return view('roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
      return  $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        // return view('roles.edit',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user_role_permission($id)
    {
       $role =  DB::table("model_has_roles")->where('model_id',$id)->first();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->role_id)
        ->leftjoin('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->pluck('permissions.name','role_has_permissions.permission_id')
            ->all();
        return response()->json([
            'user_role'=>$role,
            'user_permission'=>$rolePermissions
        ]);

    }
}