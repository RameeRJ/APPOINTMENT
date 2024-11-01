<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Appointment;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function ScheduleIndex()
    {
        $schedules = Schedule::with('doctor:id,name')->get();
        return response()->json(['schedules' => $schedules]);
    }



    public function AppointmentStore(AppointmentRequest $request)
    {
        $user = Auth::user();  
        $schedule = Schedule::findOrFail($request->schedule_id);

        $appointmentCount = Appointment::where('schedule_id', $request->schedule_id)->count();
        if ($appointmentCount >= $schedule->max_bookings) {
            return response()->json(['error' => 'No slots remaining. Maximum bookings reached.'], 400);
        }
    
        
        $lastToken = Appointment::where('schedule_id', $request->schedule_id)
                                ->max('token');
        $nextToken = $lastToken !== null ? $lastToken + 1 : 1;
    
        Appointment::create([
            'user_id' => $user->id,
            'schedule_id' => $request->schedule_id,
            'name' => $request->name,
            'age' => $request->age,
            'phone' => $request->phone,
            'place' => $request->place,
            'token' => $nextToken, 
        ]);
    
        return response()->json(['message' => 'Appointment created successfully'], 201);
    }




    public function checkLimit(Request $request)
    {
        $scheduleId = $request->input('schedule_id');
        $patientId = auth()->id(); 
        $bookingsCount = Appointment::where('schedule_id', $scheduleId)
                                    ->where('user_id', $patientId)
                                    ->count();
    
        return response()->json(['bookingsCount' => $bookingsCount]);
    }


    public function getAppointmentsByUser()
{
  
    $user = Auth::user();
    $appointments = Appointment::with(['schedule', 'schedule.doctor'])
                               ->where('user_id', $user->id)
                               ->get();
    return response()->json($appointments, 200);
}


    public function AppoinmentDestroy($id)
{
    try {
        $appointment = Appointment::findOrFail($id); 

        $appointment->delete(); 

        return response()->json(['message' => 'appointment removed successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'appointment not found or could not be deleted.'], 404);
    }
}

public function getUserDetails()
{
    $user = Auth::user();

    
    return response()->json([
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'profile_picture' => $user->profile_picture 
    ]);
}

public function updateProfile(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
        'phone' => 'required|string|regex:/^\d{10}$/',
        'profile_picture' => 'nullable|image|max:20480', 
    ]);
    
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;

  
    if ($request->hasFile('profile_picture')) {
        $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $filePath;
    }

    $user->save();

    return response()->json(['message' => 'Profile updated successfully', 'profile_pic' => $user->profile_picture]);
}

    public function getProfilePicture()
    {
        $user = Auth::user();
        
        if ($user) {

            $profilePicUrl = $user->profile_picture 
                ? '/storage/profile_picture/' . $user->profile_picture 
                : '/images/avatar/default_avatar.jpeg';
        
            return response()->json([
                'profile_picture' => $profilePicUrl,
                'username' => $user->name,
                'email' => $user->email,
            ], 200);
        }
        return response()->json(['error' => 'User not found'], 404);
    }





}
