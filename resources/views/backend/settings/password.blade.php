@extends('layouts.backend.app')

@section('title','Settings | Update Password')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('dashboard') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span> 
        </a>
       
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Change Password
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <form action="{{ route('settings.password') }}" method="POST">
                                @csrf
                                <div class="form-group form-float">
                                    <label for="exampleFormControlTextarea1">Current Password:</label>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label for="exampleFormControlTextarea1">New Password:</label>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label for="exampleFormControlTextarea1">Confirm Password:</label>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="confirm_password" required>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    
                                    <input type="submit" class="btn btn-success btn-lg custom-btn" value="Update Password">
                                </div>
        
                            </form>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
