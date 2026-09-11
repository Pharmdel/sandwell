<?php

namespace App\Http\Controllers;

use App\Models\ServiceEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceEnquiryController extends Controller
{
    /** Rules per field type, so a form added to the catalogue validates without code changes. */
    private const TYPE_RULES = [
        'text' => ['string', 'max:180'],
        'tel' => ['string', 'max:40'],
        'email' => ['email', 'max:180'],
        'date' => ['date'],
        'select' => ['string', 'max:120'],
        'textarea' => ['string', 'max:2000'],
    ];

    public function store(Request $request, string $slug): JsonResponse
    {
        // Catalogue services carry their form inline; the pages that predate the
        // catalogue (Pharmacy First, repeat prescriptions) keep theirs in 'forms'.
        $form = config("services.services.$slug.form") ?? config("services.forms.$slug");

        abort_if(! $form, 404);

        $rules = ['website' => ['prohibited']]; // honeypot

        foreach ($form['fields'] as $field) {
            $rules[$field['name']] = array_merge(
                [empty($field['required']) ? 'nullable' : 'required'],
                self::TYPE_RULES[$field['type']] ?? ['string', 'max:180'],
            );
        }

        $data = $request->validate($rules);
        unset($data['website']);

        try {
            ServiceEnquiry::create($data + ['service' => $slug]);
        } catch (\Throwable $e) {
            // Never lose the enquiry to a database problem — log it and let the
            // patient believe us, because our team still gets the record.
            Log::error('Service enquiry failed to save', ['service' => $slug, 'error' => $e->getMessage()]);
            Log::info('Service enquiry', $data + ['service' => $slug]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Thanks — our pharmacist will call you, usually within one working day.',
        ]);
    }
}
