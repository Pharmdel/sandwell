<?php

namespace App\Http\Controllers;

use App\Models\ConditionEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ConditionController extends Controller
{
    public function show(string $slug): View
    {
        $condition = collect(config('conditions.list'))->firstWhere('slug', $slug);

        abort_if(! $condition, 404);

        return view('pages.condition', ['condition' => $condition]);
    }

    public function enquire(Request $request): JsonResponse
    {
        $data = $request->validate([
            'condition' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'symptoms' => ['nullable', 'string', 'max:2000'],
            'answers' => ['nullable', 'array'],
            'website' => ['prohibited'],
        ]);

        $enquiry = ConditionEnquiry::create($data);

        Log::info("Pharmacy First enquiry #{$enquiry->id}: {$enquiry->condition} — {$enquiry->name} ({$enquiry->phone})");

        return response()->json(['ok' => true, 'id' => $enquiry->id]);
    }
}
