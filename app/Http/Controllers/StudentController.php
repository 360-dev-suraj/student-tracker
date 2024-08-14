<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAvailability;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function add(Request $request): Response
    {
        return Inertia::render('StudentForm');
    }

    public function dashboard(Request $request): Response
    {
        $students = Student::orderBy('id', 'DESC')->get();
        return Inertia::render('Dashboard', ['students' => $students]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'string|max:255',
            'dob' => 'required|date',
        ]);

        // Store the validated data into the database
        Student::create([
            'first_name' => $validatedData['firstName'],
            'middle_name' => $validatedData['middleName'] ?? "",
            'last_name' => $validatedData['lastName'],
            'dob' => $validatedData['dob'],
        ]);

        // Return a response (e.g., redirect to a different page or send a success message)
        return response()->json(['message' => 'Student created successfully']);
    }

    public function addAvailablity(Request $request, $id)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', ceil(date('j') / 7));

        if($request->input('isDisabled')) {
            $isDisabled = $request->input('isDisabled');
        }else {
            $isDisabled = false;
        }

        $data = StudentAvailability::where('student_id', $id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('week', $week)
            ->first();

        return Inertia::render('Availability', [
            'data' => $data ? $data->toArray() : null,
            'sId' => $id,
            'year' => $year,
            'month' => $month,
            'week' => $week,
            'isDisabled' => $isDisabled
        ]);
    }

    public function schedule(Request $request, $id)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', ceil(date('j') / 7));

        $data = StudentAvailability::where('student_id', $id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('week', $week)
            ->first();

        return Inertia::render('Schedule', [
            'data' => $data ? $data->toArray() : null,
            'sId' => $id,
            'year' => $year,
            'month' => $month,
            'week' => $week
        ]);
    }


    public function studentAvailablityStore(Request $request)
    {
        // Validate incoming request

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'week' => 'required|integer|between:1,4',
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create or update availability record
        $availability = StudentAvailability::updateOrCreate(
            [
                'student_id' => $request->input('student_id'),
                'year' => $request->input('year'),
                'month' => $request->input('month'),
                'week' => $request->input('week'),
            ],
            $request->only([
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
            ])
        );

        return response()->json(['message' => 'Availability updated successfully!', 'availability' => $availability]);
    }

}
