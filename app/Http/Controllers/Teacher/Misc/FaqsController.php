<?php

namespace App\Http\Controllers\Teacher\Misc;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class FaqsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Cache::remember('teacher_faqs_categories', now()->addMinutes(1440), function () {
            return Category::with(['faqs' => fn($q) => $q->forTeachers()->active()->orderBy('order')])
                ->whereHas('faqs', fn($q) => $q->forTeachers()->active())
                ->orderBy('order')
                ->get();
        });
        
        return view('teacher.misc.faqs.index', compact('categories'));
    }
}
