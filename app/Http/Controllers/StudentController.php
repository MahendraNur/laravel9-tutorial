<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StudentCreateRequest;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $student = Student::with('class')
            ->where('name', 'LIKE', '%'.$keyword.'%')
            ->orWhere('gender', $keyword)
            ->orWhere('nis', 'LIKE', '%'.$keyword.'%')
            ->orWhereHas('class', function($query) use ($keyword){
                $query->where('name', 'LIKE', '%'.$keyword.'%');
            })
            ->paginate(15);
        return view('student', ['studentList' => $student]);
    }

    public function show($id)
    {
        $student = Student::with(['class.homeroomTeacher', 'extracurriculars'])
            ->findOrFail($id);
        return view('student-detail', ['student' => $student]);
    }

    public function create()
    {
        $class = ClassRoom::select('id', 'name')->get();
        return view('student-add', ['class' => $class]);
    }

    public function store(StudentCreateRequest $request)
    {
        $pictureName = '';

        if($request->file('photo')){
        $extension = $request->file('photo')->getClientOriginalExtension();
        $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
        $request->file('photo')->storeAs('photo', $newName);
    }

        $request['image'] = $newName;
        $student=Student::create($request->all());

        if($student){
            Session::flash('status', 'success');
            Session::flash('message', 'add new student success!');
        }

        return redirect('/students');

        // $student = new Student;
        // $student->name = $request->name;
        // $student->gender = $request->gender;
        // $student->nis = $request->nis;
        // $student->class_id = $request->class_id;
        // $student->save();
    }

    public function edit(Request $request, $id)
    {
        $student = Student::with('class')->findOrFail($id);
        $class = ClassRoom::where('id', '!=', $student->class_id)->get(['id', 'name']);
        return view('student-edit', ['student' => $student, 'class' => $class]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $student->update($request->all());
        return redirect('/students');
        // $student->name = $request->name;
        // $student->gender = $request->gender;
        // $student->nis = $request->nis;
        // $student->class_id = $request->class_id;
        // $student->save();
    }

    public function delete($id)
    {
        $student = Student::findOrFail($id);
        return view('student-delete', ['student' => $student]);
    }

    public function destroy($id)
    {
        $deletedStudent = Student::findOrFail($id);
        $deletedStudent->delete();

        if($deletedStudent){
            Session::flash('status', 'success');
            Session::flash('message', 'delete student success!');
        }
        // $deleteStudent = DB::table('students')->where('id', $id)->delete();

        return redirect('/students');
    }

    public function deletedStudent()
    {
        $deletedStudent = Student::onlyTrashed()->get();
        return view('student-deleted-list', ['student' => $deletedStudent]);
    }

    public function restore($id)
    {
        $deletedStudent = Student::withTrashed()->where('id', $id)->restore();

        if($deletedStudent){
            Session::flash('status', 'success');
            Session::flash('message', 'restore student success!');
        }

        return redirect('/students');
    }

    // query builder
    // $student = DB::table('students')->get();
    // DB::table('students')->insert([
    //     'name' => 'query builder',
    //     'gender' => 'L',
    //     'nis' => '201114705',
    //     'class_id' => 3,
    // ]);
    // DB::table('students')->where('id',15)->update([
    //     'name' => 'Jess No Problem',
    //     'class_id' => 3
    // ]);
    // DB::table('students')->where('id', 17)->delete();

    // eloquent
    // $student = Student::all();
    // Student::create([
    //     'name' => 'eloquent',
    //     'gender' => 'P',
    //     'nis' => '201114706',
    //     'class_id' => 3,
    // ]);
    // Student::find(16)->update([
    //     'name' => 'eloquery 2',
    //     'class_id' => 2
    // ]);
    // Student::find(18)->delete();

    // dd($student);
    }
