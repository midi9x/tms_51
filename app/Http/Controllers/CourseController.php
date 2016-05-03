<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Subject;
use App\Models\Course;
use App\Http\Requests\AddCourseRequest;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(ENV('COURSE_PER_PAGE'));
        return view('common.course.index', ['courses' => $courses]);
    }

    public function create()
    {
        $subject = Subject::pluck('name', 'id')->all();
        return view('common.course.add', [
            'subject' => $subject
        ]);
    }

    public function store(AddCourseRequest $request)
    {
        try {
            $courseRequest = $request->only(['name', 'description']);
            $course = Course::create($courseRequest);
            $course->subjects()->attach($request->subject_id);
            return redirect()
                ->route('admin.course.index')
                ->with(['flash_message' => trans('settings.create_success')]);
        } catch (Exception $ex) {
            return redirect()
                ->route('admin.course.index')
                ->with(['flash_message' => trans('settings.error_exception')]);
        }
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('common.course.show', [
            'course' => $course
        ]);
    }

    public function edit($id)
    {
        $allSubject = $subject = Subject::pluck('name', 'id')->all();
        $course = Course::findOrFail($id);
        return view('common.course.edit', [
            'course' => $course,
            'allSubject' => $allSubject,
        ]);
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        try {
            $courseRequest = $request->only(['name', 'description']);
            $course = Course::findOrFail($id);
            $course->update($courseRequest);
            return redirect()
                ->route('admin.course.index')
                ->with(['flash_message' => trans('settings.update_success')]);

        } catch (Exception $ex) {
            return redirect()
                ->route('admin.course.index')
                ->with(['flash_message' => trans('settings.error_exception')]);
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->subjects()->detach();
            $course->delete();
            return redirect()
                ->route('admin.course.index')
                ->with(['flash_message' => trans('settings.delete_success')]);
        } catch (Exception $ex) {
            return redirect()
                ->route('admin.course.index')
                ->with(['flash_message' => trans('settings.error_exception')]);
        }
    }
}
