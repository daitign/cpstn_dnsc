<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use App\Models\Evidence;
use App\Models\EvidenceDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function evidenceDownload($id)
    {
        $evidence = Evidence::findOrFail($id);
        $file = Directory::findOrFail($evidence->directory_id);
        EvidenceDownload::create([
            'evidence_id'=>$evidence->id,
            'user_id'=>auth()->id(),
        ]);

        return Storage::download($file->location,$file->filename.'.'.$file->extension);
    }
}
