@extends('layouts.app')

@section('content')
<group-chat :user="{{ auth()->user() }}"></group-chat>
@endsection
