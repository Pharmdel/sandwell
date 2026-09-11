<?php

use App\Http\Controllers\ConditionController;
use App\Http\Controllers\ServiceEnquiryController;
use App\Http\Controllers\WeightLossEnquiryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/nhs-services', 'pages.nhs-services')->name('nhs-services');
Route::view('/private-services', 'pages.private-services')->name('private-services');
Route::view('/weight-loss-clinic', 'pages.weight-loss')->name('weight-loss');
Route::view('/pharmacy-first', 'pages.pharmacy-first')->name('pharmacy-first');
Route::view('/repeat-prescriptions', 'pages.repeat-prescriptions')->name('repeat-prescriptions');
Route::view('/branches', 'pages.branches')->name('branches');
Route::view('/shop', 'pages.shop')->name('shop');
Route::view('/book', 'pages.book')->name('book');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/health-hub', 'pages.health-hub')->name('health-hub');
Route::view('/sign-in', 'pages.sign-in')->name('sign-in');
Route::view('/terms', 'pages.terms')->name('terms');

Route::get('/health-hub/{slug}', function (string $slug) {
    $article = collect(config('sandwell.articles'))
        ->merge(config('weightloss.guides'))
        ->firstWhere('slug', $slug);

    abort_if(! $article, 404);

    return view('pages.article', ['article' => $article]);
})->name('health-hub.show');

Route::view('/services', 'pages.services')->name('services');

Route::post('/services/{slug}/enquiry', [ServiceEnquiryController::class, 'store'])->name('services.enquiry');

Route::get('/services/{slug}', function (string $slug) {
    $service = config("services.services.$slug");

    abort_if(! $service, 404);

    return view('pages.service', ['slug' => $slug, 'service' => $service]);
})->name('services.show');

Route::get('/branches/{slug}', function (string $slug) {
    $branch = collect(config('sandwell.branches'))->firstWhere('slug', $slug);

    abort_if(! $branch, 404);

    return view('pages.branch', ['branch' => $branch]);
})->name('branches.show');

Route::post('/contact', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:180'],
        'phone' => ['nullable', 'string', 'max:40'],
        'branch' => ['nullable', 'string', 'max:60'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    return redirect()->route('contact')->with('sent', true);
})->name('contact.send');

Route::prefix('weight-loss')->name('weight-loss.')->controller(WeightLossEnquiryController::class)->group(function () {
    Route::post('/consultation', 'consultation')->name('consultation');
    Route::post('/switch', 'switch')->name('switch');
    Route::post('/waitlist', 'waitlist')->name('waitlist');
});

// Pharmacy First and the minor ailments scheme are now one page; the old index
// URL is kept alive for anything already linking to it.
Route::permanentRedirect('/conditions', '/pharmacy-first');

Route::controller(ConditionController::class)->group(function () {
    Route::get('/conditions/{slug}', 'show')->name('conditions.show');
    Route::post('/conditions/enquiry', 'enquire')->name('conditions.enquiry');
});
