<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script src="{{ asset('js/app.js') }}" defer></script> <!-- DEFER PLEASE -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	</head>

	<body>
		<div id="example"> </div>
		<div id="T2" data-foo="{{$foo}}"></div>
	</body>
</html>
