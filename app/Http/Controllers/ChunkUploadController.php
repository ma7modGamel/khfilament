<?php

namespace App\Http\Controllers;
use Pion\Laravel\ChunkUpload\Exceptions\ChunkSaveException;
use Pion\Laravel\ChunkUpload\Handler\Traits\HandlerAbstract;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\Request;

class ChunkUploadController extends Controller
{
    public function upload(Request $request)
    {
        // استقبال الملف المجزأ
        $receiver = new FileReceiver('file', $request, HandlerAbstract::class);

        if ($receiver->isUploaded()) {
            // معالجة الملف المجزأ
            $save = $receiver->receive();

            // إذا تم رفع الملف بالكامل
            if ($save->isFinished()) {
                $file = $save->getFile();
                $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'local');
                return response()->json(['path' => $filePath]);
            }
        }
        return response()->json(['error' => 'File upload error'], 500);
    }
}