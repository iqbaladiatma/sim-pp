<?php

namespace App\Http\Controllers;

use App\Models\DormitoryRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DormitoryRoomController extends Controller
{
    public function index()
    {
        return Inertia::render('DormitoryRooms/Index', [
            'rooms' => DormitoryRoom::with('supervisor')->latest()->get(),
            'supervisors' => User::whereIn('role', ['ustadz', 'staff', 'admin_pondok'])->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'gender' => 'required|in:L,P',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        DormitoryRoom::create($validated);

        return redirect()->back()->with('success', 'Kamar asrama berhasil ditambahkan.');
    }

    public function update(Request $request, DormitoryRoom $room)
    {
        $validated = $request->validate([
            'building_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'gender' => 'required|in:L,P',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $room->update($validated);

        return redirect()->back()->with('success', 'Data kamar asrama diperbarui.');
    }

    public function destroy(DormitoryRoom $room)
    {
        $room->delete();
        return redirect()->back()->with('success', 'Kamar asrama berhasil dihapus.');
    }
}
