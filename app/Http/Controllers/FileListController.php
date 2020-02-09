<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Files;

class FileListController extends Controller
{
    public function index(Request $request)
    {
        // 一覧取得
        $fileList = Files::get();

        return response()->json($fileList);
    }

    public function store(Request $request)
    {
        // アップロードファイル取得
        $fileList = $request->file('fileList');

        foreach ($fileList as $file) {
            // ファイル情報を取得
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();

            // ファイルの保存・保存パスの取得
            $filePath = $file->store('.');

            // DBへ登録
            $files = new Files;
            $files->name = $fileName;
            $files->path = $filePath;
            $files->type = $fileType;
            $files->save();

            Log::info($filePath);
        }
    }

    public function destroy(Request $request)
    {
        // DBから削除
        DB::transaction(function () {
            $allFileList = Files::get();

            foreach ($allFileList as $file) {
                Files::destroy($file->id);
                Storage::delete($file->path);
            }
        });
    }
}
