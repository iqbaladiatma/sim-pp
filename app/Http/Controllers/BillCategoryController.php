<?php

namespace App\Http\Controllers;

use App\Models\BillCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Finance/Categories/Index', [
            'categories' => BillCategory::latest()->get(),
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

        return redirect()->back()->with('success', 'Kategori tagihan berhasil dibuat.');
    }

    public function update(Request $request, BillCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'type' => 'required|in:monthly,one_time',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        return redirect()->back()->with('success', 'Kategori tagihan diperbarui.');
    }

    public function destroy(BillCategory $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori tagihan dihapus.');
    }
}
