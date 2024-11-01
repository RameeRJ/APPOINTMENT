<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Http\Requests\DepartmentRequest;
use App\Models\User;
use App\Models\Doctor;
use App\Http\Requests\DoctorRequest;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller


{
    public function DepartmentIndex()
    {
        return response()->json(Department::all());
    }


    public function DepartmentStore(DepartmentRequest $request)
    {
        $department = Department::create([
            'name' => $request->name,
        ]);


        return response()->json(['message' => 'Department added successfully'], 201);
    }


    public function DepartmentDestroy($id)
    {
    $department = Department::find($id);
    
    if ($department) {
        $department->delete();
        return response()->json(['message' => 'Department deleted successfully'], 200);
    } else {
        return response()->json(['message' => 'Department not found'], 404);
    }
}

public function DoctorIndex()
{
    $doctors = Doctor::with('user')->get();
    return response()->json($doctors);
}



public function DoctorStore(DoctorRequest $request)
{

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'user_type' => 'doctor',
    ]);

    Doctor::create([
        'user_id' => $user->id,
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'department' => $request->department,
    ]);

    return response()->json(['message' => 'Doctor added successfully'], 201);
}


public function DoctorDestroy($id)
{

$doctor = Doctor::with('user')->findOrFail($id);
$user = $doctor->user;
$doctor->delete();
$user->delete();

return response()->json(['message' => 'Doctor and associated user soft deleted successfully']);
}


}
