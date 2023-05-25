<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;

use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * フォルダーと紐付いているタスクの一覧
     *
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function index(Folder $folder): \Illuminate\View\View
    {
        $folders = Auth::user()->folders()->get();

        $tasks = $folder->tasks()->get();

        return view('tasks/index',[
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
        ]);
    }

    /**
     * 新規タスク作成画面表示
     *
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function showCreateForm(Folder $folder): \Illuminate\View\View
    {
        return view('tasks/create', ['folder_id' => $folder->id]);
    }

    /**
     * タスク新規登録
     *
     * @param Folder $folder
     * @param CreateTask $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Folder $folder, CreateTask $request): \Illuminate\Http\RedirectResponse
    {
        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }

    /**
     * タスク編集画面表示
     *
     * @param Folder $folder
     * @param Task $rask
     * @return \Illuminate\View\View
     */
    public function showEditForm(Folder $folder, Task $rask): \Illuminate\View\View
    {
        $this->checkRelation($folder, $task);
        
        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    /**
     * タスク編集
     *
     * @param Folder $folder
     * @param Task $task
     * @param EditTask $request
     * @return \Illuminate\Http\redirectResponse
     */
    public function edit(Folder $folder, Task $task, EditTask $request): \Illuminate\Http\redirectResponse
    {

        $this->checkRelation($folder, $task);
        
        // 2
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        // 3
        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }

    private function checkRelation(Folder $folder, Task $task)
    {
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
    }

}