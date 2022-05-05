@extends('layouts.app')

@section('content')

@include('common.alerts')
@include('common.errors')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">App Settings</div>

        <div class="panel-body">
          <div class="col-sm-3 text-center">
            <img src="{{ $company->logo }}" style="max-width:120px; max-height:120px;">
          </div>
          <div class="col-sm-9">
            <form enctype="multipart/form-data" action="/appsettings/update" method="POST">
              {{ csrf_field() }}

              <h3 role="button" onclick="window.$('#formUpdateCompanyName').fadeToggle(); window.$('#formSubmitBox').fadeIn();">
                {{ $company->name }}<span class="glyphicon glyphicon-edit btn-edit" aria-hidden="true"></span>
              </h3>
              <input id="formUpdateCompanyName" type="text" name="name" value="{{ $company->name }}" placeholder="New name" class="form-control no-display form-company-control">

              <h3 role="button" onclick="window.$('#formUpdateCompanyEmail').fadeToggle(); window.$('#formSubmitBox').fadeIn();">
                {{ $company->email }}<span class="glyphicon glyphicon-edit btn-edit" aria-hidden="true"></span>
              </h3>
              <input id="formUpdateCompanyEmail" type="text" name="email" value="{{ $company->email }}" placeholder="New email" class="form-control no-display form-company-control">

              <h3 role="button" onclick="window.$('#formUpdateLogo').fadeToggle(); window.$('#formSubmitBox').fadeIn();">
                Change Logo
              </h3>
              <div id="formUpdateLogo" class="no-display form-company-control">
                <input type="file" name="logo" class="form-control" accept="image/png">
                <div class="help-block">Image must be a PNG file, less than 1 MB and resolution less than 1024x1024 pixels.</div>
              </div>

              <div id="formSubmitBox" class="form-group pull-right no-display form-company-control">
                <input type="submit" class="btn btn-primary">
                <input type="button" class="btn btn-default" value="Cancel" onclick="window.$('.form-company-control').fadeOut()">
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
