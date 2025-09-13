<html lang="en"><head>
	<meta charset="UTF-8">
	<title>{{ config('app.name') }} | {{isset($pageTitle)?$pageTitle:''}}</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/bootstrap3.4.1.min.css') }}">
<link rel="shortcut icon" href="{{URL::to(config('app.asset').'logo/favicon.ico')}}"/>
	<style>
		@font-face {
			font-family: 'Bree Serif';
			font-style: normal;
			font-weight: 400;
			src: local('Bree Serif Regular'), local('BreeSerif-Regular'), url('fonts/BreeSerif/BreeSerif-Regular.woff2') format('woff2');
			unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
		}
	  	body, h1, h2, h3, h4, h5, h6{
	  		font-family: 'Bree Serif', serif;
	  	}
	  	body {

		  	font-size: 12px;
		  	line-height: 1.22857143;
		}
	  	h5{
		 	font-size: 14px;
		 	margin-top: 2px;
		 	margin-bottom: 2px;
	  	 }
	   	h4{
			font-size: 16px;
			margin-top: 5px;
			margin-bottom: 5px;
	   	}
		@if ($print_id == 4)
	   	.dash-border {
			border: 1px dashed #555;
			height: 12em;
			padding: 2em 0 0 2em ;
		}
		@else
		.dash-border {
			border: 1px dashed #555;
			min-height: 12em;
			padding: 2em 0 0 2em ;
		}
		@endif
		.dash-border h4{
			text-align: center;
			margin-bottom: 2em;
		}
		.dash-border h4>span {
			border: 1px solid #555;
			border-radius: 20px;
			padding: 3px 10px;
		}
	   @media print  {
		   	.dash-border {
				border: 1px dashed #555;
				/* padding: 4em 0 0 0em; */
                @if ($print_id == 1)
                    min-height: 16em;
                @elseif ($print_id == 2)
                    height: 14em;
                @elseif ($print_id == 3)
                    height: 12em;
				@elseif ($print_id == 4)
                   height: 10em!important;
                @endif
			}

			.dash-border h4{
				
                @if ($print_id == 3)
					margin-top: -1em;
                    margin-bottom: 1em;
				@elseif ( $print_id == 4)	
					margin-top: -1em;
					margin-bottom: 1em;
                @else
					margin-top: -.6em;
                    margin-bottom: 2em;
                @endif

				text-align: center;
			}
			.dash-border p{
				margin-bottom: 10px;
			}
		   	@page{
			   size: a4 portrait;

		   	}
			h5{
				font-size: 12px;
				margin-top: 0px;
				margin-bottom: 0px;
			}


			h3{ font-size: 18px; }
			h3 .small {font-size: 65%; color: #777; }

			.pagebreak { page-break-after: always; }
			}
		
	</style>
  </head>

  <body data-new-gr-c-s-check-loaded="14.1084.0" data-gr-ext-installed="">
	<div class="text-center print_div">
		<button class="btn btn-info btn-md avoid print" id="print" style="margin: 1em 0; float: right;color: #fff;
				background-color: #17a2b8; border-color: #17a2b8;" type="button">
			<i class="fas fa-print"></i>&nbsp;Print
		</button>
	</div>
	<div class="container" id="print-div1">
		<div class="col-xs-12">

		</div>

	 <div class="row">
		@php
			$count = 0;
		@endphp
		@foreach ($studentList as  $key=>$student)
		 @php
			$count++;
			$footer = '';
			$page_style = '';

			if ($print_id == 1) {
				if ($count>9){
					$footer="pagebreak";
					$count = 0;
				}
			}
			elseif ($print_id == 2) {
				if ($count>11){
					$footer="pagebreak";
					$count = 0;
				}
			}
			elseif ($print_id == 3) {
				if ($count>13){
					$footer="pagebreak";
					$count = 0;
				}
			}
			else {
				if ($count>15){
					$footer="pagebreak"; 
					$count = 0;
				}
			}
		 @endphp
		<div class="col-xs-6 dash-border">
			<div class="row" >
				<h4><span>{{$exam_list->exam_name}}</span></h4>
			  	<div class="row">
					<div class="col-xs-7">
						<p><b>Name: {{$student->student_name}} </b></p>
					</div>
					<div class="col-xs-5">
						<p><b>SID: </b>22021462</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-7">
						<p><b>Section: </b>{{$student->section_name}}</p>
					</div>
					<div class="col-xs-5">
						<p><b>Roll: </b>{{$student->class_roll}}</p>
					</div>
					<div class="col-xs-7">
						<p><b>Class: </b>{{$student->class_name}}</p>
					</div>
					<div class="col-xs-5">
						<p><b>Year: </b>{{$student->year}}</p>
					</div>
				</div>
			</div>
		</div>

		<footer class="{{$footer}}"></footer>

	@endforeach


</div>
</body>
<script src="{{ asset(config('app.asset') . 'js/vendors.bundle.js') }}"></script>
<script src="{{ asset(config('app.asset') . 'js/jquery.print.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset(config('app.asset') . 'css/jquery.print.min.css') }}" media="print">

<style>
@media print  {
		h5{
			font-size: 12px;
			margin-top: -2px;
			margin-bottom: -2px;
		}
		h3{ font-size: 18px; }
		h3 .small {font-size: 65%; color: #777; }
		body {
	 	 	font-size: 12px;
		  	line-height: 1.12857143;
		}
		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		  	padding: 6px;
		  	line-height: 1.028571;
		  	font-size: 10px;
		}
		h2 {
			font-size: 22px;
		}
		h4 {
			font-size: 16px;
		}
		.row {
			margin-right: -5px;
			margin-left: 0px;
		}
}
</style>
<script>
$(document).ready(function() {
	$(".print_div").find("#print").on("click", function() {
		var dv_id = $(this).parents(".print_div").attr("id");
		$("#" + dv_id).print({
			//Use Global styles
			globalStyles: true,
			//Add link with attrbute media=print
			mediaPrint: false,
			iframe: true,
			//Don"t print this
			noPrintSelector: ".avoid"
		});
	});
});
</script>



