<!DOCTYPE html>

<?php $image_path = '/images/rsc.jpg'; ?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
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

		p {
			font-size: 14px;
			line-height: 1.8;
		}
		.container {
			width: 650px;
			margin: 30px auto;
			padding: 30px !important;
			overflow: hidden;
			position: relative;
			height: 980px;
		}

		.logo {
			float: left;
			height: 100px;
			width: 10%;
		}

		.logo img {
			width: 150px;
		}

		.header-right {
			text-align: center;
		}


		.header-right h2 {
			font-size: 18px;
		}

		.header-right h3 {
			font-size: 14px;
		}

		.content h2 {
			font-size: 22px;
			padding: 14px 0;
		}

		.text-center {
			text-align: center;
		}

		.table {
			margin: 25px 0;
		}
		.declration {
			margin: 20px 0;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}
		td, th {
			vertical-align: middle;
			text-align: left;
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
		/*	margin-top: 10px;*/
		position: absolute;
			bottom: 2px;
			left: 0;
			width: 100%;
		}

		.ftable {
			border-collapse: separate;
			border-spacing: 30px 15px;
		}

		.ftable td {
			border-top: 1px solid #000;
			padding: 5px 10px;
		}

		.dic {
			clear: both;
			display: block;
			width: 100%;
		}

		.text-red {
			color: #000;
		}


		.product {
			margin: 20px 0;
		}

		.items {
			font-size: 14px;
			padding-bottom: 5px;
		}
		.items strong {
			font-size: 16px;
		}
		.inp-check {
			font-weight: bold;
			font-size: 20px;
			display: inline;
		}
	
		.btable tr, .btable th, .btable td {
			border: 0 #999 solid;
			padding: 10px;
		}
		.btable {
			border: 1px solid #000;
			padding: 10px;
		}

		.ifrom {
			padding: 5px 10px;
			border: 1px solid #000;
		}




    </style>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="logo">
				<img src="{{ public_path() . $image_path }}">
			</div>
			<div class="header-right text-center">
				<h2>IT Equipment Return Form</h2>
                <h3>
                    Department of Information Technology
                </h3>
                <h3>
                    RMG Sustainability Council
                </h3>
			</div>
		</div>
		<div class="content">

			<table class="table">
				<tr>
                    <th>Employee ID </th>
                    <td class="text-center">:</td>
                    <td>{{ $employee->emply_id }}</td>
                    <th>Date </th>
                    <td class="text-center">:</td>
                    <td>
                        {{ Carbon\Carbon::parse($employee->date_of_join)->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <th>Employee Name</th>
                    <td class="text-center">:</td>
                    <td colspan="4">{{ $employee->name }}</td>
                </tr>
                <tr>
                    <th>Designation: </th>
                    <td class="text-center">:</td>
                    <td>{{ $employee->designation }}</td>

                    <th>Department: </th>
                    <td class="text-center">:</td>
                    <td>{{ $employee->department->short_name }}</td>
                </tr>

			</table>

			<div class="product">
			    
			    
			    @if( $manual == 1 )
			    
			    @foreach( $employee->transections as $transection)
			    
    			    @if( $transection->stock->producttype->slug == 'laptop' && $transection->return_date == null )
        			    @if($laptop == 1)
        				<div class="items">
        					<label> <input type="checkbox" class="inp-check"  checked > <strong>Laptop</strong>( {{ $transection->stock->product->brand. ', '. $transection->stock->product->model .', S/N: '. $transection->stock->service_tag .', '. $transection->stock->product->description }}, )</label>
        				</div>
        				@endif
    				
    				@endif
    				
    				
    				@if( $transection->stock->producttype->slug == 'laptop' && $transection->return_date == null )
				
        				@if($mobile == 1)
        				<div class="items">
        					<label> <input type="checkbox" class="inp-check"  checked > <strong> Mobile </strong>( Brand, Model, IMEI: 123456, Description )</label>
        				</div>
        				@endif
        			@endif
				
				@if($mouse == 1)
    				
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong> Mouse </strong>( Brand, Model, Description )</label>
    				</div>
    			@endif
    			
    			@if($camera == 1)
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong>Camera</strong> (Brand, Model, Description)</label>
    				</div>
				@endif
				
				@if($sdcard == 1)
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong>SD Card</strong> (Brand, Model, Description)</label>
    				</div>
				@endif
				
				
    			@if($pendrive == 1)
    				
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong> Pendrive </strong>( Brand, Model, Description )</label>
    				</div>
    			@endif
    			

				@if($laptop_bag == 1)
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong>Laptop Bag</strong> (Brand)</label>
    				</div>
				@endif
				
				
				@endforeach

				@else
				
				
				@if($laptop == 1)
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong> Laptop </strong>( Brand, Model, SN: 123456, Description )</label>
				</div>
				@endif
				
				
				@if($mobile == 1)
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong> Mobile </strong>( Brand, Model, IMEI: 123456, Description )</label>
				</div>
				@endif
				
				
				@if($pendrive == 1)
    				
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong> Pendrive </strong>( Brand, Model, Description )</label>
    				</div>
    			@endif
    			
    			
				@if($mouse == 1)
    				
    				<div class="items">
    					<label> <input type="checkbox" class="inp-check"  checked > <strong> Mouse </strong>( Brand, Model, Description )</label>
    				</div>
    			@endif
    			
				@if($laptop_bag == 1)
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong>Laptop Bag</strong> (Brand)</label>
				</div>
				@endif
    			
    			

				@endif
			</div>
			
		</div>
		<div class="footer">

			<div class="dic">
				<table class="ftable">
    				<tr class="text-center">
    					<td width="30%"> Returned By</td>
                        <td width="36%">Head of Department</td>
                        <td width="33%">Received By</td>
                        <td width="33%">Head of IT</td>
    				</tr>
				</table>
			</div>
			<p class="text-red text-center">
				<strong>
				NB: Please provide every copy of receipt acknowledgement to HR Department to keep in employee file*
				</strong>
			</p>

			
	</div>
</body>
</html>

									
