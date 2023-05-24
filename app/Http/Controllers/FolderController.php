<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Http\Requests\CreateFolder;

class FolderController extends Controller
{
    /**
     * 新規フォルダ作成画面表示
     *
     * @return \Illuminate\View\View
     */
    public function showCreateForm(): \Illuminate\View\View
    {
        return view('folders/create');
    }

    /**
     * フォルダの新規登録
     *
     * @param CreateFolder $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateFolder $request): \Illuminate\Http\RedirectResponse
    {
        $folder = new Folder();

        $folder->title = $request->title;

        $folder->save();

        return redirect()->route('tasks.index', ['id' => $folder->id]);
    }
}