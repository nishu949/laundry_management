<?php

namespace App\Http\Controllers;

use App\Models\RateCard;
use Illuminate\Http\Request;

class RateCardController extends Controller
{
    public function index()
    {
        $rateCards = RateCard::orderBy('name')->get();

        return view('rate-cards.index', compact('rateCards'));
    }

    public function create()
    {
        return view('rate-cards.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCard($request);

        RateCard::create([
            'name'         => $validated['name'],
            'service_type' => $validated['service_type'],
            'base_rate'    => $validated['base_rate'],
            'tiers'        => $this->buildTiers($request),
        ]);

        return redirect()->route('rate-cards.index')
            ->with('success', 'Rate card created.');
    }

    public function edit(RateCard $rateCard)
    {
        return view('rate-cards.edit', compact('rateCard'));
    }

    public function update(Request $request, RateCard $rateCard)
    {
        $validated = $this->validateCard($request, $rateCard->id);

        $rateCard->update([
            'name'         => $validated['name'],
            'service_type' => $validated['service_type'],
            'base_rate'    => $validated['base_rate'],
            'tiers'        => $this->buildTiers($request),
        ]);

        return redirect()->route('rate-cards.index')
            ->with('success', 'Rate card updated.');
    }

    public function destroy(RateCard $rateCard)
    {
        if ($rateCard->orderItems()->exists()) {
            return back()->with('success', 'Cannot delete: this rate card is used by existing orders.');
        }

        $rateCard->delete();

        return redirect()->route('rate-cards.index')
            ->with('success', 'Rate card deleted.');
    }



    private function validateCard(Request $request, ?int $ignoreId = null): array
    {
        $unique = 'unique:rate_cards,service_type' . ($ignoreId ? ",{$ignoreId}" : '');

        return $request->validate([
            'name'         => 'required|string|max:255',
            'service_type' => 'required|string|max:100|' . $unique,
            'base_rate'    => 'required|numeric|min:0',
        ], [
            'service_type.unique' => 'This service type code is already in use.',
        ]);
    }

    private function buildTiers(Request $request): array
    {
        $tiers = [];

        foreach ((array) $request->input('tiers', []) as $row) {
            if (!isset($row['min_quantity'], $row['rate'])) continue;
            if ($row['min_quantity'] === '' || $row['rate'] === '') continue;

            $tiers[] = [
                'min_quantity' => (int) $row['min_quantity'],
                'rate'         => (float) $row['rate'],
            ];
        }

        usort($tiers, fn ($a, $b) => $a['min_quantity'] <=> $b['min_quantity']);

        return $tiers;
    }
}