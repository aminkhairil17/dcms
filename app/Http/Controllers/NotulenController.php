<?php

namespace App\Http\Controllers;

use App\Models\Meeting; // atau model Anda
use Illuminate\Http\Request;

class NotulenController extends Controller
{
    public function view($id)
    {
        $record = Meeting::findOrFail($id);

        $filePath = storage_path('app/private/' . $record->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
