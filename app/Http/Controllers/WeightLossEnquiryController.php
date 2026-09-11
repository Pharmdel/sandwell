<?php

namespace App\Http\Controllers;

use App\Models\WeightLossEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeightLossEnquiryController extends Controller
{
    public function consultation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'best_time' => ['nullable', 'string', 'max:40'],
            'conditions' => ['nullable', 'string', 'max:1000'],
            'medication' => ['nullable', 'string', 'max:1000'],
            'nhs_number' => ['nullable', 'string', 'max:20'],
            'scr_consent' => ['nullable', 'boolean'],
            'bmi' => ['nullable', 'numeric', 'between:10,100'],
            'choice' => ['nullable', 'string', 'max:40'],
            'comfortable_with' => ['nullable', 'string', 'max:40'],
            'budget' => ['nullable', 'string', 'max:40'],
            'pace' => ['nullable', 'string', 'max:40'],
            'recommended' => ['nullable', 'string', 'max:120'],
            'website' => ['prohibited'], // honeypot
        ]);

        return $this->store('consultation', $data, $data['recommended'] ?? 'Consultation (BMI below 30)');
    }

    public function switch(Request $request): JsonResponse
    {
        $data = $request->validate([
            'medication' => ['required', 'string', 'max:60'],
            'dose' => ['required', 'string', 'max:60'],
            'provider' => ['nullable', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'website' => ['prohibited'],
        ]);

        return $this->store('switch', $data, 'Switch — '.$data['medication']);
    }

    public function waitlist(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'height_cm' => ['nullable', 'numeric', 'between:100,250'],
            'weight_kg' => ['nullable', 'numeric', 'between:30,400'],
            'bmi' => ['nullable', 'numeric', 'between:10,100'],
            'current_treatment' => ['nullable', 'string', 'max:40'],
            'contact_pref' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'website' => ['prohibited'],
        ]);

        return $this->store('waitlist', $data, 'Foundayo (orforglipron) waitlist');
    }

    private function store(string $kind, array $data, string $treatment): JsonResponse
    {
        $enquiry = WeightLossEnquiry::create([
            'kind' => $kind,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'treatment' => $treatment,
            'payload' => collect($data)->except(['name', 'phone', 'email', 'website'])->all(),
        ]);

        Log::info("Weight loss {$kind} enquiry #{$enquiry->id} from {$enquiry->name} ({$enquiry->phone}) — {$treatment}");

        return response()->json(['ok' => true, 'id' => $enquiry->id]);
    }
}
