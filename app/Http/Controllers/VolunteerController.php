<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'help_type' => 'required|string|max:255',
            'hours_committed' => 'required|integer|min:1|max:100',
            'phone' => 'required|string|max:20',
            'details' => 'nullable|string|max:1000'
        ]);

        Volunteer::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'help_type' => $request->help_type,
            'hours_committed' => $request->hours_committed,
            'phone' => $request->phone,
            'details' => $request->details,
            'status' => 'pending'
        ]);

        return redirect()->back()
            ->with('success', '¡Te has postulado con éxito! Hemos recibido tu solicitud.');
    }

    public function admin()
    {
        $volunteers = \App\Models\Volunteer::with(['user', 'product'])
                        ->latest()
                        ->get();
        return view('volunteers.admin', compact('volunteers'));
    }

    public function destroy($id)
    {
        \App\Models\Volunteer::findOrFail($id)->delete();
        return redirect()->route('volunteers.admin')->with('success', 'Registro de voluntariado eliminado.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,accepted,rejected']);
        $volunteer = \App\Models\Volunteer::findOrFail($id);
        $volunteer->update(['status' => $request->status]);
        return redirect()->route('volunteers.admin')->with('success', 'Estado del voluntariado actualizado a ' . $request->status . '.');
    }

    public function myApplications()
    {
        $volunteers = \App\Models\Volunteer::with('product')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();
        return view('volunteers.index', compact('volunteers'));
    }
}
