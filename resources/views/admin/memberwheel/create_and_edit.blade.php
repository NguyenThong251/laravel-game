@extends('layouts.baseframe')
@php
$isUpdated = isset($model->id);
$title = $isUpdated?"Wheel Winning Data Modification":"Wheel Winning DataAdded"
@endphp

@section('title', $title ?? '')

@section('content')
<div class="col-sm-12">

    <div class="card">
        <div class="card-header">
            <h4>{{ $title }}</h4>
            <ul class="card-actions">
                <li>
                    <button type="button" onclick="javascript:window.history.go(-1);">
                        <i class="mdi mdi-skip-backward"></i>return
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">

            <form method="post" class="form-horizontal"
                action="{{ $isUpdated?route('admin.memberwheels.update',['memberwheel' => $model->id]):route('admin.memberwheels.store') }}"
                id="form">

                @csrf

                @if($isUpdated)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $model->id }}">
                @endif

                <div class="form-group">
<label class="col-sm-2 control-label ">Member ID</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="member_id" placeholder="Please enter Member ID" value="{{ $isUpdated?$model->member_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Administrator ID</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="user_id" placeholder="Please enter your administrator ID" value="{{ $isUpdated?$model->user_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Prize ID</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="award_id" placeholder="Please enter Prize ID" value="{{ $isUpdated?$model->award_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Prize Description</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="award_desc" placeholder="Please enter Prize Description" value="{{ $isUpdated?$model->award_desc:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Collection status</label>
<div class="col-sm-4">
<select name="status" class="form-control js_select2">
<option value="">@lang('res.common.select_default')</option>
@foreach(config('platform.wheel_status') as $key =>$value)
<option value="{{ $key }}" @if($isUpdated && $model->status == $key) selected
@endif>{{ $value }}</option>
@endforeach
</select>
</div>
</div>


                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" data-operate="ajax-submit" type="button"  >Save content</button>
                        <button class="btn btn-default" type="reset">Reset</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection


@section('footer-js')
<script src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
{{-- <script src="http://libs.itshubao.com/tinymce/tinymce.min.js"></script> --}}
<script>
    $(function () {

        

    });

</script>
@endsection
