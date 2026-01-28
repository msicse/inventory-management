@extends('layouts.backend.app')

@section('title', 'Executive Dashboard | Stock Overview')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .table td {
            vertical-align: middle !important;
        }

        .hover-zoom-effect:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .info-box-3 {
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .info-box-3 .content {
            padding-right: 15px;
            overflow: hidden;
        }

        .info-box-3 .content .number {
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info-box-3 .content .text {
            font-size: 11px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .progress {
            height: 25px;
            margin-bottom: 0;
            background-color: #e0e0e0;
            position: relative;
        }

        .progress-bar {
            line-height: 25px;
            font-weight: bold;
            font-size: 12px;
            transition: width 0.6s ease;
        }

        .progress-bar:empty:before {
            content: "0%";
            position: absolute;
            left: 10px;
            color: #666;
        }

        .stock-badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            min-width: 100px;
            text-align: center;
        }

        .stock-critical {
            background: #f44336;
            color: white;
        }

        .stock-low {
            background: #ff9800;
            color: white;
        }

        .stock-moderate {
            background: #ffc107;
            color: #333;
        }

        .stock-good {
            background: #4caf50;
            color: white;
        }

        .card .header {
            padding: 20px;
        }

        .card .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .badge {
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .table > tbody > tr > td {
            padding: 12px 8px;
        }

        @media (max-width: 768px) {
            .info-box-3 .content .number {
                font-size: 24px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Executive Summary Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-cyan hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">category</i>
                    </div>
                    <div class="content">
                        <div class="text">PRODUCT TYPES</div>
                        <div class="number" style="font-size: 28px; font-weight: bold;">{{ $stocks->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-green hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">inventory</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL ITEMS</div>
                        <div class="number" style="font-size: 28px; font-weight: bold;">{{ number_format($totalItems) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-orange hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">assignment_turned_in</i>
                    </div>
                    <div class="content">
                        <div class="text">ASSIGNED</div>
                        <div class="number" style="font-size: 28px; font-weight: bold;">{{ number_format($totalAssigned) }}</div>
                        <small style="font-size: 10px; display: block; margin-top: 3px; opacity: 0.9;">{{ $totalItems > 0 ? number_format(($totalAssigned/$totalItems)*100, 1) : 0 }}% Utilization</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-light-blue hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">monetization_on</i>
                    </div>
                    <div class="content" style="padding-right: 15px;">
                        <div class="text">TOTAL VALUE</div>
                        <div class="number" style="font-size: 18px; font-weight: bold; word-break: break-word; line-height: 1.1;">${{ number_format($totalValue, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row clearfix" style="margin-top: 15px;">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header bg-cyan">
                        <h2 style="color: white; font-size: 16px;">
                            <i class="material-icons" style="vertical-align: middle;">pie_chart</i>
                            STOCK DISTRIBUTION BY CATEGORY
                        </h2>
                    </div>
                    <div class="body" style="height: 350px;">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header bg-green">
                        <h2 style="color: white; font-size: 16px;">
                            <i class="material-icons" style="vertical-align: middle;">bar_chart</i>
                            TOP 10 CATEGORIES BY VOLUME
                        </h2>
                    </div>
                    <div class="body" style="height: 350px;">
                        <canvas id="volumeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Performance Table -->
        <div class="row clearfix" style="margin-top: 15px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-indigo">
                        <h2 style="color: white; font-size: 18px;">
                            <i class="material-icons" style="vertical-align: middle;">assessment</i>
                            CATEGORY PERFORMANCE OVERVIEW
                        </h2>
                    </div>
                    <div class="body">
                        <!-- Filter Options -->
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Search Product Type</label>
                                    <input type="text" id="searchProduct" class="form-control" placeholder="Search by product type...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter by Health Status</label>
                                    <select id="filterHealth" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="HEALTHY">Healthy</option>
                                        <option value="MODERATE">Moderate</option>
                                        <option value="LOW STOCK">Low Stock</option>
                                        <option value="OUT OF STOCK">Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter by Availability</label>
                                    <select id="filterAvailability" class="form-control">
                                        <option value="">All Items</option>
                                        <option value="available">Has Available Stock</option>
                                        <option value="low">Low Available (<5)</option>
                                        <option value="none">No Available</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" id="resetFilters" class="btn btn-warning btn-block waves-effect">
                                        <i class="material-icons" style="vertical-align: middle; font-size: 18px;">refresh</i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 50px;">SL</th>
                                        <th style="min-width: 150px;">Product Type</th>
                                        <th style="text-align: center; width: 80px;">Total</th>
                                        <th style="text-align: center; width: 90px;">Assigned</th>
                                        <th style="text-align: center; width: 90px;">Available</th>
                                        <th style="text-align: center; width: 180px;">Utilization</th>
                                        <th style="text-align: center; width: 150px;">Stock Level</th>
                                        <th style="text-align: center; width: 130px;">Health Status</th>
                                        <th style="text-align: center; width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $key => $data)
                                        @php
                                            $total = $data->stocks->count();
                                            $assigned = $data->stocks->where('is_assigned', 1)->count();
                                            $available = $data->stocks->where('is_assigned', 2)->count();
                                            $utilization = $total > 0 ? ($assigned / $total) * 100 : 0;

                                            // Determine stock health
                                            if ($total == 0) {
                                                $healthClass = 'stock-critical';
                                                $healthText = 'OUT OF STOCK';
                                                $progressColor = 'danger';
                                            } elseif ($available < 5) {
                                                $healthClass = 'stock-low';
                                                $healthText = 'LOW STOCK';
                                                $progressColor = 'warning';
                                            } elseif ($available < 20) {
                                                $healthClass = 'stock-moderate';
                                                $healthText = 'MODERATE';
                                                $progressColor = 'info';
                                            } else {
                                                $healthClass = 'stock-good';
                                                $healthText = 'HEALTHY';
                                                $progressColor = 'success';
                                            }
                                        @endphp
                                        <tr>
                                            <td style="text-align: center;">{{ $key + 1 }}</td>
                                            <td><strong style="font-size: 14px;">{{ $data->name }}</strong></td>
                                            <td style="text-align: center;"><span class="badge bg-blue">{{ $total }}</span></td>
                                            <td style="text-align: center;"><span class="badge bg-green">{{ $assigned }}</span></td>
                                            <td style="text-align: center;"><span class="badge bg-orange">{{ $available }}</span></td>
                                            <td style="width: 180px;">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-{{ $progressColor }}"
                                                         role="progressbar"
                                                         style="width: {{ max(min($utilization, 100), ($utilization > 0 ? 5 : 0)) }}%; min-width: {{ $utilization > 0 ? '40px' : '0' }};"
                                                         aria-valuenow="{{ $utilization }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        {{ number_format($utilization, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 150px;">
                                                <div class="progress" style="height: 20px;">
                                                    @php
                                                        $stockPercent = $total > 0 ? ($available / $total) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar progress-bar-{{ $progressColor }}"
                                                         role="progressbar"
                                                         style="width: {{ max(min($stockPercent, 100), ($stockPercent > 0 ? 8 : 0)) }}%; min-width: {{ $available > 0 ? '50px' : '0' }};"
                                                         aria-valuenow="{{ $available }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="{{ $total }}">
                                                        {{ $available }}/{{ $total }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="stock-badge {{ $healthClass }}">{{ $healthText }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="{{ route('reports.inventory') }}?type={{ $data->id }}"
                                                   class="btn btn-info btn-sm waves-effect"
                                                   title="View Detailed Inventory">
                                                    <i class="material-icons" style="font-size: 18px;">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Get the DataTable instance (already initialized by jquery-datatable.js)
            var table = $('.js-exportable').DataTable();

            // Search filter
            $('#searchProduct').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });

            // Health status filter
            $('#filterHealth').on('change', function() {
                table.column(7).search(this.value).draw();
            });

            // Availability filter
            $('#filterAvailability').on('change', function() {
                var value = this.value;
                if (value === '') {
                    table.column(4).search('').draw();
                } else {
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var available = parseInt(data[4].replace(/[^0-9]/g, '')) || 0;
                            if (value === 'available' && available > 0) return true;
                            if (value === 'low' && available > 0 && available < 5) return true;
                            if (value === 'none' && available === 0) return true;
                            if (value === '') return true;
                            return false;
                        }
                    );
                    table.draw();
                    $.fn.dataTable.ext.search.pop();
                }
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#searchProduct').val('');
                $('#filterHealth').val('');
                $('#filterAvailability').val('');
                table.search('').columns().search('').draw();
            });

            // Prepare data for charts
            var stockData = @json($stocks);
            var labels = [];
            var totals = [];
            var assigned = [];
            var available = [];
            var colors = [
                '#00bcd4', '#4caf50', '#ff9800', '#f44336', '#9c27b0',
                '#3f51b5', '#ffeb3b', '#795548', '#607d8b', '#e91e63'
            ];

            stockData.forEach(function(item) {
                labels.push(item.name);
                totals.push(item.stocks.length);
                assigned.push(item.stocks.filter(s => s.is_assigned == 1).length);
                available.push(item.stocks.filter(s => s.is_assigned == 2).length);
            });

            // Distribution Chart (Doughnut)
            var distributionCtx = document.getElementById('distributionChart').getContext('2d');
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: totals,
                        backgroundColor: colors,
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
                                padding: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Volume Chart (Horizontal Bar) - Top 10
            var sortedData = stockData.map((item, index) => ({
                name: item.name,
                total: item.stocks.length,
                color: colors[index % colors.length]
            })).sort((a, b) => b.total - a.total).slice(0, 10);

            var volumeCtx = document.getElementById('volumeChart').getContext('2d');
            new Chart(volumeCtx, {
                type: 'bar',
                data: {
                    labels: sortedData.map(d => d.name),
                    datasets: [{
                        label: 'Total Items',
                        data: sortedData.map(d => d.total),
                        backgroundColor: sortedData.map(d => d.color),
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
@endpush
