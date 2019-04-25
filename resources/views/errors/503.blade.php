@extends('errors.layout')

@php
  $error_number = "Be Right Back !!!";
@endphp
@section('more_styles')
  <style>
    .error_number {
      font-size: 56px;
      font-weight: 600;
      color: #1F1D5B;
      line-height: 100px;
    }
    .error_number small {
      font-size: 36px;
      font-weight: 700;
    }

    .error_number hr {
      margin-top: 60px;
      margin-bottom: 0;
      border-top: 5px solid #dd4b39;
      width: 50px;
    }

    .error_title {
      margin-top: 40px;
      font-size: 36px;
      color: #40C7F4;
      font-weight: 400;
    }

    .error_description {
      font-size: 24px;
      color: #40C7F4;
      font-weight: 400;
    }
  </style>

 
@endsection

@section('title')
 Apologies 
@endsection

@section('description')
  @php
    $default_error_message = config('app.name') . " is down for maintenance. Please try again later.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
@endsection
