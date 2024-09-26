@extends('layouts.baseframe')
@php
$isUpdated = isset($model->id);
$title = $isUpdated?"Member operation log modification":"Member operation log Added"
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
                action="{{ $isUpdated?route('admin.memberlogs.update',['memberlog' => $model->id]):route('admin.memberlogs.store') }}"
                id="form">

                @csrf

                @if($isUpdated)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $model->id }}">
                @endif

                <div class="form-group">
<label class="col-sm-2 control-label ">Member ID</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="member_id" placeholder="Please enter Member ID" value="{{ $isUpdated?$model->member_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">operateIP</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="ip" placeholder="Please enter the operation IP" value="{{ $isUpdated?$model->ip:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">IP real address</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="address" placeholder="Please enter the IP real address" value="{{ $isUpdated?$model->address:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">operating environment</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="ua" placeholder="Please enter the operating environment" value="{{ $isUpdated?$model->ua:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">operate type</label>
<div class="col-sm-4">
<select name="type" class="form-control js_select2">
<option value="">@lang('res.common.select_default')</option>
@foreach(config('platform.member_log_type') as $key =>$value)
<option value="{{ $key }}" @if($isUpdated && $model->type == $key) selected
@endif>{{ $value }}</option>
@endforeach
</select>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">operate Description</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="description" placeholder="Please enter an operation Description" value="{{ $isUpdated?$model->description:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Remark</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="remark" placeholder="Please enter a comment" value="{{ $isUpdated?$model->remark:"" }}" @if(!$isUpdated) required @endif>
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
