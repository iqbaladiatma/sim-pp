<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('SuperAdmin/Tenants/Index', [
            'tenants' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:tenants,slug',
            'email' => 'nullable|email|max:255',
            'subscription_plan' => 'required|in:basic,pro,enterprise',
            'max_students' => 'required|integer|min:0',
        ]);

        Tenant::create($validated);

        return redirect()->back()->with('success', 'Tenant berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:tenants,slug,' . $tenant->id,
            'email' => 'nullable|email|max:255',
            'subscription_plan' => 'required|in:basic,pro,enterprise',
            'subscription_status' => 'required|in:active,inactive,trial,expired',
            'max_students' => 'required|integer|min:0',
        ]);

        $tenant->update($validated);

        return redirect()->back()->with('success', 'Data tenant berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->back()->with('success', 'Tenant berhasil dihapus.');
    }
}
