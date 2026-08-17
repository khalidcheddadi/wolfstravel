<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PostImportService;
use Illuminate\Http\Request;

class PostImportController extends Controller
{
    public function __construct(private PostImportService $postImportService)
    {
    }

    public function index()
    {
        return view('admin.post-import.index');
    }

    public function import(Request $request)
    {
        $limit = (int) ($request->input('limit') ?: 8);
        $result = $this->postImportService->import($limit);

        return back()->with('success', 'Se importaron ' . $result['created'] . ' artículos. Se omitieron ' . $result['skipped'] . '.');
    }
}
