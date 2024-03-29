@php
	use App\Invoice;
	$inv = new Invoice;
	$inv->setBusiness($business);
@endphp
@extends('layout')

@section('content')
<style>
	.btn { padding: 4px 6px; color: #fff; background: #0e2daa; font-family: sans-serif; text-decoration: none; }
	.btn:hover { background: #ff00aa; color: #fff; }
	.btn:active { background: #fa0a00; color: #fff; }
</style>
	<h3>Business: <a href="{{url('invoices?business_id='.$business->id)}}">{{$business->name}}</a></h3>
	<hr>
	@if($invoice)
		<table border="1" cellpadding="6" style="border-collapse: collapse;">
			<thead>
				<tr>
					<td>ID</td>
					<td>Ref</td>
					<td>Customer</td>
					<td>Date</td>
					<td>Due Date</td>
					<td>SubTotal</td>
					<td>Total</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{$invoice['trans_no']}}</td>
					<td>{{$invoice['ref']}}</td>
					<td>{{$invoice['debtor_ref'] ?? $invoice['customer']['debtor_ref']}}</td>
					<td>{{$invoice['order_date']}}</td>
					<td>{{$invoice['due_date']}}</td>
					<td>{{$invoice['sub_total']}} {{$invoice['customer']['curr_code']}} </td>
					<td>{{$invoice['display_total']}} {{$invoice['customer']['curr_code']}} </td>
				</tr>
			</tbody>
		</table>
		<hr>
		<a href="{{$inv->template_url($invoice['trans_no'])}}" class="btn" target="_blank">Template</a>
		<a href="{{$inv->pdf_url($invoice['trans_no'])}}" class="btn" target="_blank">PDF</a>
		<a href="{{$inv->view_url($invoice['trans_no'])}}" class="btn" target="_blank">View Invoice</a>
		<a href="{{$inv->xml_url($invoice['trans_no'])}}" class="btn" target="_blank">Generate XML</a>
		<a href="{{$inv->xml_file_url($invoice['trans_no'])}}" class="btn" target="_blank">XML File</a>
		<a href="{{$inv->encode_xml_url($invoice['trans_no'])}}" class="btn" target="_blank">Base64 Encode</a>

		<br><br>
		<h3>QR code</h3>
		@if($qrCode)
			<img src="{!!$qrCode!!}" alt=''>
		@endif
	@endif
@endsection