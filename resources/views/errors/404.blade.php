@extends('errors.layout')

@section('code')
404
@endsection

@section('title')
Page not found
@endsection

@section('heading')
We can’t find that page.
@endsection

@section('message')
The link may be out of date, or the address mistyped. These might be what you were after.
@endsection

@section('links')
    <li><a class="underline decoration-brand-forest/40 underline-offset-4 hover:decoration-brand-forest" href="{{ url('/services') }}">All services</a></li>
    <li><a class="underline decoration-brand-forest/40 underline-offset-4 hover:decoration-brand-forest" href="{{ url('/branches') }}">Our branches</a></li>
    <li><a class="underline decoration-brand-forest/40 underline-offset-4 hover:decoration-brand-forest" href="{{ url('/pharmacy-first') }}">Pharmacy First</a></li>
    <li><a class="underline decoration-brand-forest/40 underline-offset-4 hover:decoration-brand-forest" href="{{ url('/contact') }}">Contact us</a></li>
@endsection
