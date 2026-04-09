<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupPoint;
use App\Models\PickupSlot;
use Illuminate\Http\Request;

class PickupPointController extends Controller
{
    public function index()
    {
        $points = PickupPoint::withCount('slots')->get();
        return view('admin.pickup-points.index', compact('points'));
    }

    public function create()
    {
        return view('admin.pickup-points.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'postal_code'   => 'nullable|string|max:10',
            'description'   => 'nullable|string',
            'contact_phone' => 'nullable|string|max:30',
            'active'        => 'boolean',
        ]);

        $data['active'] = $request->boolean('active', true);
        $point = PickupPoint::create($data);

        // Créer les créneaux
        $this->syncSlots($point, $request->input('slots', []));

        return redirect()->route('admin.pickup-points.index')->with('success', 'Point de retrait créé.');
    }

    public function edit(PickupPoint $pickupPoint)
    {
        $pickupPoint->load('slots');
        $days = $this->daysMap();
        return view('admin.pickup-points.edit', compact('pickupPoint', 'days'));
    }

    public function update(Request $request, PickupPoint $pickupPoint)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'postal_code'   => 'nullable|string|max:10',
            'description'   => 'nullable|string',
            'contact_phone' => 'nullable|string|max:30',
            'active'        => 'boolean',
        ]);

        $data['active'] = $request->boolean('active');
        $pickupPoint->update($data);

        // Mettre à jour les créneaux
        $pickupPoint->slots()->delete();
        $this->syncSlots($pickupPoint, $request->input('slots', []));

        return redirect()->route('admin.pickup-points.index')->with('success', 'Point de retrait mis à jour.');
    }

    public function destroy(PickupPoint $pickupPoint)
    {
        $pickupPoint->delete();
        return back()->with('success', 'Point de retrait supprimé.');
    }

    private function syncSlots(PickupPoint $point, array $slots): void
    {
        foreach ($slots as $slot) {
            if (empty($slot['day_of_week']) || empty($slot['open_time']) || empty($slot['close_time'])) {
                continue;
            }
            PickupSlot::create([
                'pickup_point_id' => $point->id,
                'day_of_week'     => (int) $slot['day_of_week'],
                'open_time'       => $slot['open_time'],
                'close_time'      => $slot['close_time'],
                'active'          => !empty($slot['active']),
            ]);
        }
    }

    public function daysMap(): array
    {
        return [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            0 => 'Dimanche',
        ];
    }
}
