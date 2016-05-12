<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Request;
use App\Models\UserTask;
use App\Http\Requests;
use Response;
use Auth;

class UserTaskController extends Controller
{
    public function update($id)
    {
        if (Request::ajax()) {
            $usertask = UserTask::findOrFail($id);
            $usertask->update(['status' => UserTask::USER_TASK_FINISH]);
            $task = $usertask->task;
            $subject = $task->subject;
            //finish user_subject if all user_task is finish
            $subject->finishSubject();
            //create activity
            $subject->activities()->create(['user_id' => Auth::user()->id, 'description' => trans('settings.finish_task') . ' ' . $task->name]);
            return Response::json(['success' => true]);
        }
        return Response::json(['success' => false, 'messages' => trans('settings.error_exception')]);
    }
}
