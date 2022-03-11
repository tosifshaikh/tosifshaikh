<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;
    function __construct(Role $role)
    {
        $this->role = $role;

    }
    public function create(Request $request)
    {
        $this->validate($request,[
            'roleName' => 'required',
        ]);

        return $this->role->create([
            'roleName' => $request->roleName
        ]);
    }
    public function getData(Request $request)
    {
        return $this->role->get();
    }
    public function edit(Request $request)
    {
        $this->validate($request,[
            'roleName' => 'required',
        ]);
        return $this->role->where('id',$request->id)->update(['roleName' => $request->roleName]);
    }
    public function assignRole(Request $request)
    {
        $this->validate($request,[
            'permission' => 'required',
            'id' => 'required',
        ]);
        return $this->role->where('id',$request->id)->update(['permission' => $request->permission]);
    }
}
