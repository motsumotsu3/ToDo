<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;

class TaskController extends Controller
{
    /**
     * フォルダーと紐付いているタスクの一覧
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function index(int $id): \Illuminate\View\View
    {
        $folders = Folder::all();

        $current_folder = Folder::find($id);

        $tasks = $current_folder->tasks()->get();

        return view('tasks/index',[
            'folders' => $folders,
            'current_folder_id' => $id,
            'tasks' => $tasks,
        ]);
    }

    /**
     * 新規タスク作成画面表示
     *
     * @param integer $id
     * @return \Illuminate\View\View
     */
    public function showCreateForm(int $id): \Illuminate\View\View
    {
        return view('tasks/create', ['folder_id' => $id]);
    }

    /**
     * タスク新規登録
     *
     * @param integer $id
     * @param CreateTask $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateTask $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $current_folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'id' => $current_folder->id,
        ]);
    }

    /**
     * タスク編集画面表示
     *
     * @param integer $id
     * @param integer $task_id
     * @return \Illuminate\View\View
     */
    public function showEditForm(int $id, int $task_id): \Illuminate\View\View
    {
        $task = Task::find($task_id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    /**
     * タスク編集
     *
     * @param integer $id
     * @param integer $task_id
     * @param EditTask $request
     * @return \Illuminate\Http\redirectResponse
     */
    public function edit(int $id, int $task_id, EditTask $request): \Illuminate\Http\redirectResponse
    {
        // 1
        $task = Task::find($task_id);

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

}