@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        User LDAP Info<small> </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
              @php
                 dump(Auth::User()->ldapinfo()->toArray());
              @endphp
            </div>
        </div>
    </div>
@endsection
