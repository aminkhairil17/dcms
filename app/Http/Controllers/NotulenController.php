<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotulenController extends Controller
{
    use AuthorizesRequests;

    public function view($id)
    {
        $record = Meeting::findOrFail($id);

        $this->authorize('view', $record);

        $filePath = storage_path('app/private/'.$record->file_path);

        if (! file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
