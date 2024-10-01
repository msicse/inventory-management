@extends('layouts.backend.app')

@section('title','Admin | Dashboard')

@section('content')

<div class="container-fluid">
	<div class="block-header">
		<h2>DASHBOARD</h2>
	</div>

	<div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text">Total Employees</div>
                    <div class="number count-to" data-from="0" data-to="{{ $employees->count() }}" data-speed="15" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">favorite</i>
                </div>
                <div class="content">
                    <div class="text">Active Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $alaptops->count() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">library_books</i>
                </div>
                <div class="content">
                    <div class="text">Poor Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $plaptops->count() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">person_add</i>
                </div>
                <div class="content">
                    <div class="text">Damage Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $dlaptops->count() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
    </div>

	<div class="row clearfix">
        <!-- Task Info -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Employees</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Products Count</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $key => $employee)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->designation }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>{{ $employee->transections->count() }}</td>
                                  
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Task Info -->
    </div>

</div>

@endsection

@push('js')
<!-- Jquery CountTo Plugin Js -->
<script src="{{ asset('backend/plugins/jquery-countto/jquery.countTo.js') }}"></script>

<!-- Morris Plugin Js -->
<script src="{{ asset('backend/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('backend/plugins/morrisjs/morris.js') }}"></script>

<!-- Sparkline Chart Plugin Js -->
<script src="{{ asset('backend/plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>

<script src="{{ asset('backend/js/pages/index.js') }}"></script>
@endpush