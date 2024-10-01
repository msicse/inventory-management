
<?php $image_path = '/images/rsc.jpg'; ?>
<!DOCTYPE html>
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

        td,
        th {
            vertical-align: middle;
            text-align: left;
        }

        .table table,
        .table th,
        .table tr,
        .table td {
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
            bottom: 0;
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
            color: #ff0000;
        }


        .product {
            margin: 20px 0;
        }

        .items {
            font-size: 14px;
            padding-bottom: 10px;
        }

        .items strong {
            font-size: 16px;
        }

        .inp-check {
            font-weight: bold;
            font-size: 20px;
            display: inline;
        }

        .btable tr,
        .btable th,
        .btable td {
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
                <h2>Acknowledgement of Receipt of IT Property</h2>
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
                        {{ Carbon\Carbon::parse($isdate)->format('d M Y') }}
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

            <p class="declration">
                I hereby acknowledge receipt and assignment of the following company property. 
				<strong class="text-red">I agree to maintain the equipment in good condition and promise to report any loss or damage immediately. I agree to assume all financial responsibility  for damages other than malfunctions beyond my control.</strong> I further agree to use said property only for work-related purposes.

            </p>


            <div class="product">
                @foreach ($transections as $transection)
                    @if($transection->stock->producttype->slug == 'laptop')
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong>{{ $transection->stock->producttype->name }} </strong>( {{ $transection->stock->product->brand.", ". $transection->stock->product->model. ", ". " Serial:". $transection->stock->service_tag.", ". $transection->stock->product->description  }} )</label>
				</div>

				@elseif($transection->stock->producttype->slug == 'monitor')
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong>{{ $transection->stock->producttype->name }} </strong>( {{ $transection->stock->product->brand.", ". $transection->stock->product->model. ", ". " Serial:". $transection->stock->service_tag.", ". $transection->stock->product->description  }} )</label>
				</div>
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong> Monitor Power Adapter  </strong></label>
				</div>
					<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong> HDMI Cable  </strong></label>
				</div>
				
				@elseif($transection->stock->producttype->slug == 'mobile')
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong>{{ $transection->stock->producttype->name }} </strong>( {{ $transection->stock->product->brand.", ". $transection->stock->product->model. ", ". " Serial:". $transection->stock->service_tag.", ". $transection->stock->product->description  }} )</label>
				</div>
				
				@else
				<div class="items">
					<label> <input type="checkbox" class="inp-check"  checked > <strong>{{ $transection->stock->producttype->name }} </strong>( {{ $transection->stock->product->brand.", ". $transection->stock->product->model. ", ". " IMEI: ". $transection->stock->service_tag.", ". $transection->stock->product->description  }} )</label>
				</div>
				
				@endif
                @endforeach
            </div>

            <p>
                <strong>Return of Property:</strong> In the event of my resignation/termination from employment, I will return all
                company property (specified above or on attached sheet) upon my last day of work (or as specified by my
                line-manager).
            </p>
            <p class="text-red">
                <strong>
                    If any property is not returned, I authorize a reasonable value for such items to be deducted from
                    my final paycheck.
                </strong>
            </p>

        </div>
        <div class="footer">

            <div class="dic">
				<table class="ftable">
				<tr>
					<td width="30%"> Received By</td>
					<td width="36%">Head of Department</td>
					<td width="33%">Delivered By</td>
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
