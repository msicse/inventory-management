@extends('layouts.backend.app')

@section('title','Settings | Update Profile')

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
                        Update Profile
                    </h2>
                </div>
                <div class="body">
                    <form class="" action="{{ route('settings.profile') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label">Name</label>
                                    <div class="form-line">
                                        <input type="text" value="{{ empty(old('name')) ? Auth::user()->name : old('name') }}" id="title" name="name" class="form-control" placeholder="Enter Name">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Username</label>
                                    <div class="form-line">
                                        <input type="text" value="{{ Auth::user()->username }}" id="title" name="username" class="form-control" placeholder="Enter Username" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group form-float">
                                    <label class="form-label">Image </label>
                                    <div class="form-line">
                                        <input type="file" value="{{ old('image') }}" id="email" name="image" class="form-control" placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <img src="{{ asset('images/users/'. Auth::user()->image) }}" alt="" style="height: 120px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group form-float">
                                    <label class="form-label">Phone</label>
                                    <div class="form-line">
                                        <input type="text" value="{{ empty(old('phone')) ? Auth::user()->phone : old('phone') }}" id="phone" name="phone" class="form-control" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Email</label>
                                    <div class="form-line">
                                        <input type="text" value="{{  Auth::user()->email }}" id="email" name="email" class="form-control" placeholder="Enter email" readonly>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">About</label>
                                    <div class="form-line">
                                        <textarea name="about" class="form-control" rows="5">{{ empty(old('about')) ? Auth::user()->about : old('about')}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <input type="submit" name="" value="Save" class="btn btn-primary btn-lg custom-btn" style="padding: 12px 60px;">
                            </div>
                        </div>
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

