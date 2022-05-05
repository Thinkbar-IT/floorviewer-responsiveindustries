@extends('layouts.app')

@section('content')

@include('common.alerts')
@include('common.errors')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Your profile</div>

                <div class="panel-body">
                    <div class="col-sm-4 text-center">
                        <img src="{{ $user->avatar }}" style="max-width:150px; max-height:150px;">
                    </div>
                    <div class="col-sm-8">
                        <h3 role="button" onclick="window.$('#formUpdateProfile').slideToggle()">{{ $user->name }} <span class="glyphicon glyphicon-edit btn-edit" aria-hidden="true"></span></h3>
                        <div id="formUpdateProfile" class="form-group no-display">
                            <form action="/profile/update/name" method="POST">
                                {{ csrf_field() }}
                                <span class="col-sm-6"><input type="text" name="name" value="{{ $user->name }}" placeholder="New name" class="form-control" required></span>
                                <input type="submit" class="btn btn-primary">
                                <input type="button" class="btn btn-default" value="Cancel" onclick="window.$('#formUpdateProfile').slideUp()">
                            </form>
                        </div>

                        <h3>{{ $user->email }}</h3>

                        <form enctype="multipart/form-data" action="/profile/update/avatar" method="POST">
                            <h3 role="button" onclick="window.$('#formUpdateAvatar').slideToggle()">Update Profile Image</h3>
                            <div id="formUpdateAvatar" class="no-display">
                                {{ csrf_field() }}
                                <span class="col-sm-6"><input type="file" name="avatar" class="form-control" accept="image/*" required></span>
                                <input type="submit" class="btn btn-primary">
                                <input type="button" class="btn btn-default" value="Cancel" onclick="window.$('#formUpdateAvatar').slideUp()">
                                <div>Image must be less than 1 MB and resolution less than 1024x1024 pixels.</div>
                            </div>
                        </form>

                        <h3 role="button" onclick="window.$('#formChangePassword').slideToggle()">
                            @if ($user->password)
                            Change password
                            @else
                            Set password
                            @endif
                        </h3>
                        <div id="formChangePassword" class="form-horizontal no-display">
                            <form enctype="multipart/form-data" action="/profile/update/password" method="POST">
                                {{ csrf_field() }}
                                @if ($user->password)
                                <div class="form-group required">
                                    <label for="oldPassword" class="col-sm-5 control-label">Old Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" name="oldPassword" placeholder="Old Password"  class="form-control" required>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group required">
                                    <label for="newPassword" class="col-sm-5 control-label">New Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" name="newPassword" placeholder="New Password"  class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label for="newPasswordConfirm" class="col-sm-5 control-label">Confirm New Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" name="newPassword_confirmation" placeholder="Confirm New Password"  class="form-control" required>
                                        <div class="help-block">New password must contain 8 or more characters.</div>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-primary">
                                    <input type="button" class="btn btn-default" value="Cancel" onclick="window.$('#formChangePassword').slideUp()">
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
