<?php

namespace App\Http\Controllers;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Http\Requests\ScheduleRequest;
use Illuminate\Http\Request;
use App\Models\Appointment;

class DoctorController extends Controller
{


    public function ScheduleIndex($doctorId)
    {
        $schedules = Schedule::where('doctor_id', $doctorId)->get(); 
        return response()->json(['schedules' => $schedules]);
    }


    public function ScheduleStore(ScheduleRequest $request)
    {

       $schedule = Schedule::create([
            'doctor_id'     => $request->doctor_id,
            'title' => $request->title,
            'date'  => $request->date,
            'start_time'  => $request->start_time,
            'end_time'  => $request->end_time,
            'max_bookings'  => $request->max_bookings,
  

        ]);
        return response()->json([
            'message' => 'Schedule created successfully!',
            'schedule' => $schedule
        ], 201);
    }

    public function ScheduleDestroy($id)
{
    try {
        $schedule = Schedule::findOrFail($id); 

        $schedule->delete(); 

        return response()->json(['message' => 'Schedule removed successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Schedule not found or could not be deleted.'], 404);
    }
}


    public function GetDoctorId(Request $request)
    {
      
        $request->validate([
            'email' => 'required|email',
        ]);
 
        $doctor = Doctor::where('email', $request->email)->first();
       
        if ($doctor) {    
            return response()->json(['doctor_id' => $doctor->id], 200);
        }

        return response()->json(['message' => 'Doctor not found'], 404);
    }

    
    
public function GetAppointments(Request $request)
{

    $appointments = Appointment::with(['schedule', 'user'])->get();

    return response()->json($appointments);
}

public function AppointmentBySchedule($doctorId)
{
    $appointmentsBySchedule = Schedule::where('doctor_id', $doctorId)
        ->withCount('appointments')
        ->get();

    return response()->json($appointmentsBySchedule);
}

public function PrescriptionStore(Request $request, $id)
{

    $request->validate([
        'prescription' => 'required|file|mimes:pdf,jpg,jpeg,png|max:204800', 
    ]);
    $appointment = Appointment::findOrFail($id);

    if ($request->hasFile('prescription')) {

        $file = $request->file('prescription');

        $filename = $file->getClientOriginalName();

        $path = $file->storeAs('prescriptions', $filename, 'public');

        $appointment->prescription = $path;

        $appointment->save();

        return response()->json(['message' => 'Prescription uploaded successfully']);
    }

    return response()->json(['error' => 'No file uploaded'], 400);
}





}
