<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * AJAX search for offers by id or customer name for select2
     */
    public function search(Request $request)
    {
        $term = trim((string) $request->get('term', ''));

        $query = Offer::query();

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                if (is_numeric($term)) {
                    $q->orWhere('id', (int) $term);
                }
                $q->orWhere('customer->name', 'like', "%{$term}%");
            });
        }

        $offers = $query->orderByDesc('created_at')->limit(20)->get(['id', 'customer', 'status', 'type_id']);

        return response()->json([
            'results' => $offers->map(function (Offer $offer) {
                $customerName = $offer->customer['name'] ?? 'N/A';
                return [
                    'id' => $offer->id,
                    'text' => __('Offer') . ' #' . $offer->id . ' - ' . $customerName,
                ];
            }),
        ]);
    }

    /**
     * Get an offer full payload to prefill contract form
     */
    public function data(Offer $offer)
    {
        return response()->json([
            'id' => $offer->id,
            'customer_id' => $offer->customer_id,
            'customer' => $offer->customer,
            'type_id' => $offer->type_id,
            'container_price' => $offer->container_price,
            'no_containers' => $offer->no_containers,
            'monthly_dumping_cont' => $offer->monthly_dumping_cont,
            'monthly_total_dumping_cost' => $offer->monthly_total_dumping_cost,
            'additional_trip_cost' => $offer->additional_trip_cost,
            'contract_period' => $offer->contract_period,
            'tax_value' => $offer->tax_value,
            'start_date' => optional($offer->start_date)->format('Y-m-d'),
            'end_date' => optional($offer->end_date)->format('Y-m-d'),
            'notes' => $offer->notes,
            'agreement_terms' => $offer->agreement_terms,
            'material_restrictions' => $offer->material_restrictions,
            'delivery_terms' => $offer->delivery_terms,
            'payment_policy' => $offer->payment_policy,
            'valid_until' => optional($offer->valid_until)->format('Y-m-d'),
            'status' => $offer->status,
        ]);
    }
}


