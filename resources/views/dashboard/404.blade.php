@extends('dashboard.layouts.master2')
@section('css')
<!--- Internal Fontawesome css-->
<link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!---Ionicons css-->
<link href="{{URL::asset('assets/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
<!---Internal Typicons css-->
<link href="{{URL::asset('assets/plugins/typicons.font/typicons.css')}}" rel="stylesheet">
<!---Internal Feather css-->
<link href="{{URL::asset('assets/plugins/feather/feather.css')}}" rel="stylesheet">
<!---Internal Falg-icons css-->
<link href="{{URL::asset('assets/plugins/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">
@endsection
@section('content')
		<!-- Main-error-wrapper -->
		<div class="main-error-wrapper  page page-h " style="font-family: Cairocard-header bg-transparent">
			<img src="{{URL::asset('assets/img/media/404.png')}}" class="error-page" alt="error">
			<h2>عذراً، الصفحة غير متوفرة</h2>
			<h6></h6><a style="font-family: 'Cairo'" class="btn btn-danger" href="{{ url('/' . $page='home') }}">العودة إلى الصفحة الرئيسية</a>
		</div>
		<!-- /Main-error-wrapper -->
@endsection
@section('js')
@endsection
