<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Files;

class FileController extends Controller
{
    public function index(Request $request)
    {
        // リクエスト
        $param = $request->all();

        $fileInfo = Files::find($param['id']);

        $header = [
            'Content-Type' => $fileInfo->type,
            'Content-disposition' => "attachment; filename=". urlencode($fileInfo->name),
        ];

        return response()->file(storage_path('app/'. $fileInfo->path), $header);
    }

    public function store(Request $request)
    {
        // アップロードファイル取得
        $file = $request->file('file');

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

    public function destroy(Request $request)
    {
        $param = $request->all();

        // DBから削除
        DB::transaction(function () use ($param) {
            Files::destroy($param['id']);
            Storage::delete($param['path']);
        });
    }
}
