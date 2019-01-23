@extends('layouts.master')
@section('title','個人OKR')
@section('content')
{!! \Session::put('redirect_url', \Request::getRequestUri()) !!}
@include('okrs.list', ['actionlist'=>true,'admin' => $owner->id, 'routeSearch' => route('user.okr',$owner->id),
'routeObjectiveStore' => route('user.objective.store', auth()->user()->id)])
@endsection