<!DOCTYPE html>
<html lang="en">
<?php $image_path = '/images/rsc.jpg'; ?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RSC | GRN | IT |{{ $purchase->invoice_no }}</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" href="{{ public_path()}}/images/favicon.png" type="image/png">

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Calibri, sans-serif;
            width: 100%;
            height: 100%;
            font-size: 14px;
        }

        .container {
            width: 980px;
            margin: 0 auto;
            padding: 10px 15px;
			/* height: 100%; */
			min-height: 700px;
			
			position: relative;
        }
        .header{
            padding-top: 45px;
        }

        .logo {
            float: left;
        }

        .logo img {
            width: 160px;
        }

        .header-right {
            text-align: center;
        }
        .header-right h2 {
            font-size: 19px;
        }
        .content h2 {
            font-size: 22px;
            text-align: center;
            padding: 14px 0;
        }

        .text-center {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            vertical-align: middle;
        }
        .table table, .table th, .table tr, .table td {
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 5px;	
        
        }

        .no-border {
            border-bottom: 0 #000 solid !important;
        }

        .footer {
            /* margin-top: 20px; */
			position: absolute;
			bottom: 0;
			left: auto;
			width: 100%;
			height: 150px;
			

        }

        .ftable {
            border-collapse: separate;
            border-spacing: 20px 50px;
        }

        .ftable td {
            border-top: 1px solid #000;
            padding: 5px 10px;
        }


    </style>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="logo">
			    
				<img src="{{ public_path() . $image_path }}">
			</div>
			<div class="header-right">
				<h2>RMG Sustainability Council [RSC]</h2>
				<p>
					Level-13,AJ Heights, Cha/72/1/D, Pragati Sarani, North Badda, Dhaka-1212, Bangladesh 
				</p>
				<p>
					PABX: +880 2 41081863-66    Web: www.rsc-bd.org   Email: palash.kumar@rsc-bd.org
				</p>
			</div>
		</div>
		<div class="content">
			<h2> Goods Receipt Note  </h2>
			<table class="table">
				<tr>
					<th colspan="5" style="text-align: left;">GRN:  RSC/IT/{{ $purchase->invoice_no }}</th>
					<th>GRN Date</th>
					<th colspan="2">{{ Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</th>
					<th rowspan="5">Remarks</th>
				</tr>
				<tr>
					<td rowspan="3" colspan="5">
						From, <br>
						{{ $purchase->supplier->company }} 
						<br>
						{{ $purchase->supplier->address }} 

					</td>
					<td>Bill No:  </td>
					<td colspan="2">{{ $purchase->reference_invoice }}</td>
				</tr>
				<tr>
					<td>Challan No: </td>
					<td colspan="2">{{ $purchase->challan_no }} </td>
					
				</tr>
				<tr>
					<td>Vehicle No:	</td>
					<td colspan="2"></td>
				</tr>

				<tr>
					<th>SL No.</th>
					<th>PO/Bill No.</th>
					<th>Items code</th>
					<th>Items Description</th>
					<th>Deprtment</th>
					<th>UOM	</th>
					<th>Challan Qty.</th>
					<th>Received Qty.</th>
				</tr>
				@foreach( $purchase->products as $key => $ppd )
				<tr class="text-center">
					<td>{{ $key +1 }}</td>
					<td style="text-align: left;">{{ $purchase->reference_invoice }}</td>
					<td></td>
					<td>{{ $ppd->product->brand ." ".$ppd->product->model ." ". $ppd->product->description }} </td>
					<td>IT</td>
					<td>{{ $ppd->product->unit }}</td>
					<td>{{ $ppd->quantity }}</td>
					<td>{{ $ppd->quantity }}</td>
					<td></td>
				</tr>
				@endforeach

				
			</table>
			
		</div>
		<div class="footer">

			<table class="ftable">
				<tr>
					<td>Prepared By</td>
					<td>Received By</td>
					<td>Inspected By</td>
					<td>Head of Department</td>
					<td>Authorized By</td>
				</tr>
			</table>
	</div>
</body>
</html>

									
