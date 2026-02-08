<?php

namespace App\Http\Controllers;

use App\Models\BillCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BillCategory::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Finance/Categories/Index', [
            'categories' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'type' => 'required|in:monthly,one_time',
            'description' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        BillCategory::create($validated);

        return redirect()->back()->with('success', 'Kategori tagihan berhasil ditambahkan.');
    }

    public function update(Request $request, BillCategory $billCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'type' => 'required|in:monthly,one_time',
            'description' => 'nullable|string',
        ]);

        $billCategory->update($validated);

        return redirect()->back()->with('success', 'Kategori tagihan berhasil diperbarui.');
    }

    public function destroy(BillCategory $billCategory)
    {
        $billCategory->delete();

        return redirect()->back()->with('success', 'Kategori tagihan berhasil dihapus.');
    }
}
