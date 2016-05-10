@extends('lbhurtado::sms')

@section('header')
{!! $header !!}
@stop

@section('body')
{!! $body !!}
@stop

@section('footer')
{!! $footer ?: "-HQ"!!}
@stop
