<?php
namespace App\Http\Controllers;

use App\Models\CybersecurityNews;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsController extends Controller
{
    public function index(Request $request) {
        $filters = $request->validate([
            'q' => 'nullable|string|max:255',
            'day' => 'nullable|integer|between:1,31',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|between:1,9999',
        ]);

        if (! empty($filters['day']) && ! empty($filters['month']) && ! empty($filters['year'])
            && ! checkdate((int) $filters['month'], (int) $filters['day'], (int) $filters['year'])) {
            throw ValidationException::withMessages(['day' => 'Tanggal tidak valid.']);
        }

        $news = CybersecurityNews::query()
            ->when($request->filled('q'), function ($query) use ($filters) {
                $query->where(function ($query) use ($filters) {
                    $query->where('title', 'like', '%'.$filters['q'].'%')
                        ->orWhere('description', 'like', '%'.$filters['q'].'%');
                });
            })
            ->when($request->filled('day'), fn ($query) => $query->whereDay('date', $filters['day']))
            ->when($request->filled('month'), fn ($query) => $query->whereMonth('date', $filters['month']))
            ->when($request->filled('year'), fn ($query) => $query->whereYear('date', $filters['year']))
            ->orderByDesc('date')->orderByDesc('id')->paginate(6)->withQueryString();

        // Get available years for the filter (SQLite compatible)
        $availableYears = CybersecurityNews::selectRaw("DISTINCT strftime('%Y', date) as year")
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();

        return view('news.index', compact('news', 'availableYears'));
    }

    public function show($id) {
        $news = CybersecurityNews::findOrFail($id);
        return view('news.show', compact('news'));
    }
}