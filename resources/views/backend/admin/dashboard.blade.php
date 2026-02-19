@extends('layouts.backend.app')

@section('title', 'Admin | Dashboard')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .dashboard-stat-card {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.12);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 0;
            overflow: hidden;
        }

        .dashboard-stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        }

        .info-box {
            margin-bottom: 0;
            border-radius: 10px 10px 0 0;
            min-height: 140px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent 60%, rgba(255,255,255,0.1) 100%);
            pointer-events: none;
        }

        .info-box .icon {
            font-size: 60px;
            opacity: 0.95;
            transition: transform 0.3s;
        }

        .dashboard-stat-card:hover .info-box .icon {
            transform: scale(1.1) rotate(5deg);
        }

        .info-box .content {
            padding-right: 15px;
            flex: 1;
        }

        .info-box .content .text {
            font-size: 14px;
            margin-bottom: 8px;
            line-height: 1.3;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.95;
        }

        .info-box .content .number {
            font-size: 36px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .info-box .content small {
            display: block;
            font-size: 12px;
            opacity: 0.9;
            color: #fff !important;
            margin-top: 5px;
            line-height: 1.3;
            font-weight: 500;
        }

        .stat-link {
            display: block;
            text-align: center;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            font-weight: 700;
            text-decoration: none !important;
            border-radius: 0 0 10px 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: inset 0 -2px 0 rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .stat-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .stat-link:hover::before {
            width: 300px;
            height: 300px;
        }

        .stat-link:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: #ffffff !important;
            text-decoration: none !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .stat-link:focus,
        .stat-link:active,
        .stat-link:visited {
            color: #ffffff !important;
            text-decoration: none !important;
        }

        .alert-card {
            border-left: 4px solid;
            border-radius: 6px;
            margin-bottom: 12px;
            padding: 15px 18px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .alert-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .alert-critical { border-left-color: #f44336; }
        .alert-warning { border-left-color: #ff9800; }
        .alert-info { border-left-color: #00bcd4; }

        .utilization-circle {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 800;
            color: white;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2), inset 0 2px 4px rgba(255,255,255,0.3);
            position: relative;
        }

        .utilization-circle::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .recent-activity-item {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }

        .recent-activity-item:hover {
            background: #f8f9fa;
        }

        .recent-activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .category-bar {
            background: #e0e0e0;
            height: 28px;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 12px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .category-bar-fill {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 12px;
            color: white;
            font-size: 12px;
            font-weight: 700;
            transition: width 0.5s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .card .header {
            border-radius: 10px 10px 0 0;
            padding: 15px 20px;
        }

        .card .body {
            padding: 20px;
        }

        @media (max-width: 768px) {
            .info-box .icon {
                font-size: 50px;
            }
            .info-box .content .number {
                font-size: 28px;
            }
            .utilization-circle {
                width: 100px;
                height: 100px;
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>EXECUTIVE DASHBOARD</h2>
        <small>Real-time inventory management overview</small>
    </div>

    <!-- Quick Stats Row -->
    <div class="row clearfix" style="margin-bottom: 15px;">

        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">wc</i>
                </div>
                <div class="content">
                    <div class="text">Active Employees</div>
                    <div class="number count-to" data-from="0" data-to="{{ $employees->count() }}" data-speed="15"
                        data-fresh-interval="20">{{ $employees->count() }}</div>
                </div>
            </div>
            <a href="{{ route('employees.index') }}" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-teal hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">laptop</i>
                </div>
                <div class="content">
                    <div class="text">Total Laptops</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_laptop }}" data-speed="1000"
                        data-fresh-interval="20">{{ $total_laptop }}</div>
                </div>
            </div>
            <a href="{{ route('reports.inventory') }}?type=1" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">phone_android</i>
                </div>
                <div class="content">
                    <div class="text">Total Mobiles</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_mobile }}" data-speed="1000"
                        data-fresh-interval="20">{{ $total_mobile }}</div>
                </div>
            </div>
            <a href="{{ route('reports.inventory') }}?type=19" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-blue hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">devices</i>
                </div>
                <div class="content">
                    <div class="text">Total Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total }}" data-speed="1000"
                        data-fresh-interval="20">{{ $total }}</div>
                </div>
            </div>
            <a href="{{ route('reports.inventory') }}" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-red hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">update</i>
                </div>
                <div class="content">
                    <div class="text">Pending Tag Update</div>
                    <div class="number count-to" data-from="0" data-to="{{ pending_tag() }}" data-speed="15"
                        data-fresh-interval="20">{{ pending_tag() }}</div>
                </div>
            </div>
            <a href="{{ route('inventories.pending') }}" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">laptop</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Laptop</div>
                    <div class="number count-to" data-from="0" data-to="{{ $assigned_laptop }}" data-speed="1000"
                        data-fresh-interval="20">{{ $assigned_laptop }}</div>
                </div>
            </div>
            <a href="{{ route('transections.index') }}" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-deep-orange hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">phone_android</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Mobiles</div>
                    <div class="number count-to" data-from="0" data-to="{{ $assigned_mobile }}" data-speed="1000"
                        data-fresh-interval="20">{{ $assigned_mobile }}</div>
                </div>
            </div>
            <a href="{{ route('transections.index') }}" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-light-blue hover-expand-effect dashboard-stat-card">
                <div class="icon">
                    <i class="material-icons">devices</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_assigned }}" data-speed="1000"
                        data-fresh-interval="20">{{ $total_assigned }}</div>
                </div>
            </div>
            <a href="{{ route('transections.index') }}" class="stat-link">View Details →</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-purple hover-expand-effect dashboard-stat-card">
                <div class="icon"><i class="material-icons">inventory</i></div>
                <div class="content">
                    <div class="text">Available Stock</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_available }}" data-speed="1000" data-fresh-interval="20">{{ $total_available }}</div>
                </div>
            </div>
            <a href="{{ route('reports.inventory') }}?assignment_status=available" class="stat-link">View Available →</a>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row clearfix" style="margin-top: 10px; margin-bottom: 15px;">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="card dashboard-stat-card">
                <div class="body text-center" style="padding: 30px 20px;">
                    @php
                        $circleColor = $utilizationRate >= 80 ? '#4caf50' : ($utilizationRate >= 60 ? '#ff9800' : '#f44336');
                    @endphp
                    <div class="utilization-circle" style="background: {{ $circleColor }};">
                        {{ $utilizationRate }}%
                    </div>
                    <h5 style="margin-top: 20px; margin-bottom: 8px; font-weight: 700; font-size: 15px;">Asset Utilization</h5>
                    <p style="margin: 0; color: #666; font-size: 13px; font-weight: 500;">{{ $total_assigned }}/{{ $total }} items in use</p>
                </div>
            </div>
        </div>



        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-deep-purple hover-expand-effect dashboard-stat-card">
                <div class="icon"><i class="material-icons">warning</i></div>
                <div class="content">
                    <div class="text">Warranty Expiring</div>
                    <div class="number count-to" data-from="0" data-to="{{ $warrantyExpiring }}" data-speed="1000" data-fresh-interval="20">{{ $warrantyExpiring }}</div>
                    <small>Within 30 days</small>
                </div>
            </div>
            <a href="{{ route('reports.inventory') }}?warranty_status=expiring" class="stat-link">View Details →</a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-expand-effect dashboard-stat-card">
                <div class="icon"><i class="material-icons">shopping_cart</i></div>
                <div class="content">
                    <div class="text">This Month Purchases</div>
                    <div class="number count-to" data-from="0" data-to="{{ $purchase }}" data-speed="1000" data-fresh-interval="20">{{ $purchase }}</div>
                </div>
            </div>
            <a href="{{ route('purchases.index') }}" class="stat-link">View Purchases →</a>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="row clearfix" style="margin-top: 10px; margin-bottom: 15px;">
        <div class="col-lg-12">
            <div class="card">
                <div class="header bg-red">
                    <h2 style="color: white;"><i class="material-icons" style="vertical-align: middle;">notifications_active</i> CRITICAL ALERTS</h2>
                </div>
                <div class="body">
                    <div class="row">
                        @if($criticalAlerts['warranty_expired'] > 0)
                        <div class="col-md-4">
                            <div class="alert-card alert-critical">
                                <i class="material-icons" style="vertical-align: middle; color: #f44336;">error</i>
                                <strong>{{ $criticalAlerts['warranty_expired'] }}</strong> items with expired warranty
                                <a href="{{ route('reports.inventory') }}?warranty_status=expired" style="float: right; color: #f44336;">View →</a>
                            </div>
                        </div>
                        @endif

                        @if($criticalAlerts['out_of_stock'] > 0)
                        <div class="col-md-4">
                            <div class="alert-card alert-critical">
                                <i class="material-icons" style="vertical-align: middle; color: #f44336;">remove_shopping_cart</i>
                                <strong>{{ $criticalAlerts['out_of_stock'] }}</strong> categories out of stock
                                <a href="{{ route('reports.stocks') }}" style="float: right; color: #f44336;">View →</a>
                            </div>
                        </div>
                        @endif

                        @if($criticalAlerts['low_stock'] > 0)
                        <div class="col-md-4">
                            <div class="alert-card alert-warning">
                                <i class="material-icons" style="vertical-align: middle; color: #ff9800;">inventory_2</i>
                                <strong>{{ $criticalAlerts['low_stock'] }}</strong> categories low on stock
                                <a href="{{ route('reports.stocks') }}" style="float: right; color: #ff9800;">View →</a>
                            </div>
                        </div>
                        @endif

                        @if($criticalAlerts['pending_tag'] > 0)
                        <div class="col-md-4">
                            <div class="alert-card alert-warning">
                                <i class="material-icons" style="vertical-align: middle; color: #ff9800;">local_offer</i>
                                <strong>{{ $criticalAlerts['pending_tag'] }}</strong> items pending tag update
                                <a href="{{ route('inventories.pending') }}" style="float: right; color: #ff9800;">Update →</a>
                            </div>
                        </div>
                        @endif

                        @if($criticalAlerts['warranty_expiring'] > 0)
                        <div class="col-md-4">
                            <div class="alert-card alert-info">
                                <i class="material-icons" style="vertical-align: middle; color: #00bcd4;">schedule</i>
                                <strong>{{ $criticalAlerts['warranty_expiring'] }}</strong> warranties expiring soon
                                <a href="{{ route('reports.inventory') }}?warranty_status=expiring" style="float: right; color: #00bcd4;">View →</a>
                            </div>
                        </div>
                        @endif

                        @if(array_sum($criticalAlerts) == 0)
                        <div class="col-md-12 text-center" style="padding: 30px;">
                            <i class="material-icons" style="font-size: 48px; color: #4caf50;">check_circle</i>
                            <h4 style="color: #4caf50; margin-top: 10px;">All systems operational!</h4>
                            <p style="color: #666;">No critical alerts at this time.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Row -->
    <div class="row clearfix" style="margin-top: 10px; margin-bottom: 15px;">
        <div class="col-lg-6">
            <div class="card">
                <div class="header bg-indigo">
                    <h2 style="color: white;"><i class="material-icons" style="vertical-align: middle;">trending_up</i> PURCHASE TREND</h2>
                </div>
                <div class="body" style="min-height: 300px; padding: 20px;">
                    <canvas id="purchaseTrendChart" style="width: 100%; height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="header bg-teal">
                    <h2 style="color: white;"><i class="material-icons" style="vertical-align: middle;">pie_chart</i> ASSET CONDITION</h2>
                </div>
                <div class="body" style="min-height: 300px; padding: 20px;">
                    <canvas id="conditionChart" style="width: 100%; height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Categories and Recent Activity -->
    <div class="row clearfix" style="margin-top: 10px; margin-bottom: 15px;">
        <div class="col-lg-6">
            <div class="card">
                <div class="header bg-cyan">
                    <h2 style="color: white;"><i class="material-icons" style="vertical-align: middle;">leaderboard</i> TOP 5 CATEGORIES</h2>
                </div>
                <div class="body">
                    @foreach($topCategories as $category)
                        @php
                            $percentage = $total > 0 ? ($category->stocks_count / $total) * 100 : 0;
                            $colors = ['#00bcd4', '#4caf50', '#ff9800', '#9c27b0', '#f44336'];
                            $color = $colors[$loop->index % 5];
                        @endphp
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <strong>{{ $category->name }}</strong>
                                <span>{{ $category->stocks_count }} items ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="category-bar">
                                <div class="category-bar-fill" style="width: {{ min($percentage, 100) }}%; background: {{ $color }};">
                                    @if($percentage > 15)
                                        {{ number_format($percentage, 1) }}%
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('reports.stocks') }}" class="btn btn-primary btn-block waves-effect" style="margin-top: 15px;">
                        View Full Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="header bg-green">
                    <h2 style="color: white;"><i class="material-icons" style="vertical-align: middle;">history</i> RECENT PURCHASES</h2>
                </div>
                <div class="body">
                    @forelse($recentPurchases as $recentPurchase)
                        <div class="recent-activity-item">
                            <div class="activity-icon" style="background: #e3f2fd;">
                                <i class="material-icons" style="color: #2196f3;">shopping_bag</i>
                            </div>
                            <div style="flex: 1;">
                                <strong>{{ $recentPurchase->supplier->company ?? 'N/A' }}</strong>
                                <br>
                                <small style="color: #666;">{{ $recentPurchase->purchase_date ? \Carbon\Carbon::parse($recentPurchase->purchase_date)->format('M d, Y') : 'N/A' }}</small>
                            </div>
                            <div>
                                <span class="badge bg-blue">{{ $recentPurchase->purchaseProducts->count() ?? 0 }} items</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center" style="padding: 30px; color: #999;">No recent purchases</p>
                    @endforelse
                    <a href="{{ route('purchases.index') }}" class="btn btn-success btn-block waves-effect" style="margin-top: 15px;">
                        View All Purchases
                    </a>
                </div>
            </div>
        </div>
    </div>
{{--
    <div class="row clearfix">
        <!-- Task Info -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Products will Expire <small>within 90days</small></h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                                <tr>
                                    <th>S.L</th>
                                    <th>Product</th>
                                    <th>Purchase Date</th>
                                    <th>Expired Date</th>
                                    <th>Renew Days Remain</th>
                                    <th>Vendor</th>
                                    <th>Contact </th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expired_product->orderBy('expired_date', 'ASC')->get() as $key => $data)
                                    @php
                                        $datework = Carbon\Carbon::now();
                                        $days = $datework->diffInDays($data->expired_date);

                                        if ($days > 60 && $days <= 90) {
                                            $text = 'tinfo';
                                        } else if ($days > 30 && $days <= 60) {
                                            $text = 'orange';
                                        } else {
                                            $text = 'tdanger';
                                        }
                                    @endphp

                                    @if($data->producttype->slug == 'software')
                                        <tr class="{{ $text }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->product->title }}</td>
                                            <td>{{ $data->purchase_date }}</td>
                                            <td>{{ $data->expired_date }}</td>

                                            <td>{{ $days }} </td>
                                            <td>{{ $data->purchase->supplier->company }}</td>
                                            <td>{{ $data->purchase->supplier->phone }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Task Info -->
    </div>

--}}
</div>

@endsection

@push('js')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-countto/jquery.countTo.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize CountTo plugin for stat numbers
            $('.count-to').countTo();

            // Purchase Trend Chart
            var trendCtx = document.getElementById('purchaseTrendChart').getContext('2d');
            var trendData = @json($monthlyTrend);

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.month),
                    datasets: [{
                        label: 'Purchases',
                        data: trendData.map(d => d.count),
                        borderColor: '#00bcd4',
                        backgroundColor: 'rgba(0, 188, 212, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#00bcd4',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { size: 12 },
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 12 }
                            }
                        }
                    }
                }
            });

            // Asset Condition Chart
            var conditionCtx = document.getElementById('conditionChart').getContext('2d');
            var conditionData = @json($conditionStats);

            var conditionLabels = [];
            var conditionCounts = [];
            var conditionColors = {
                'Good': '#4caf50',
                'Fair': '#ff9800',
                'Poor': '#f44336',
                'Excellent': '#00bcd4',
                'Damaged': '#9c27b0',
                'New': '#2196f3'
            };

            if (conditionData && conditionData.length > 0) {
                conditionData.forEach(function(item) {
                    var label = item.asset_condition || 'Unknown';
                    conditionLabels.push(label);
                    conditionCounts.push(item.count);
                });
            } else {
                // Fallback data if no conditions exist
                conditionLabels = ['No Data'];
                conditionCounts = [1];
            }

            new Chart(conditionCtx, {
                type: 'doughnut',
                data: {
                    labels: conditionLabels,
                    datasets: [{
                        data: conditionCounts,
                        backgroundColor: conditionLabels.map(label => conditionColors[label] || '#607d8b'),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 12 },
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
