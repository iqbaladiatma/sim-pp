<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WhatsappLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WhatsappLog::latest();

        if ($request->has('search')) {
            $query->where('phone_number', 'like', '%' . $request->search . '%')
                ->orWhere('message', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('System/WhatsappLogs/Index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['search'])
        ]);
    }
}
