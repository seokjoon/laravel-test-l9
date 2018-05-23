
@extends('layouts.master')
@section('style')
	<style>
		body { background: green; color: white; }
	</style>
@stop
@section('content')
	<p>저는 자식 뷰의 'content' 섹션입니다.</p>
	@include('partials.footer')
@stop
@section('script')
	<script>alert("저는 자식 뷰의 'script' 섹션입니다.")</script>
@stop

<ul>
	@forelse($items as $item)
		<li>{{ $item }}</li>
	@empty
		<li>없음</li>
	@endforelse
</ul>
<ul>
	@foreach($items as $item)
		<li>{{ $item }}</li>
	@endforeach
</ul>

@if($itemCount = count($items)) {{ $itemCount }} 종류 과일
@else 아무것도 없음
@endif <br/>

{{ isset($greeting) ? $greeting : 'Hello ' . $name }} <br />
{{ $greeting or 'Hello ' }} {{ $name or '' }} <br />
{!! $greeting !!} <br />