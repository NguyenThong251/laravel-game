@extends('layouts.baseframe')
@php
$isUpdated = isset($model->id);
// $title = $isUpdated?"代理返点修改":"代理返点Added"
@endphp

@section('title', $_title)

@section('content')
<div class="col-sm-12">

    <div class="card">
        <div class="card-header">
            <h4>{{ $_title }}</h4>
            <ul class="card-actions">
                <li>
                    <button type="button" onclick="javascript:window.history.go(-1);">
                        <i class="mdi mdi-skip-backward"></i> @lang('res.btn.back')
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">

            <form method="post" class="form-horizontal"
                action="{{ $isUpdated?route('admin.agentfdrates.update',['agentfdrate' => $model->id]):route('admin.agentfdrates.store') }}"
                id="form">

                @csrf

                @if($isUpdated)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $model->id }}">
                @endif

                <div class="form-group">
<label class="col-sm-2 control-label ">父级Proxy ID</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="parent_id" placeholder="Please enter 父级Proxy ID" value="{{ $isUpdated?$model->parent_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Current Member ID</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="member_id" placeholder="Please enter Current Member ID" value="{{ $isUpdated?$model->member_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label required">Game type</label>
<div class="col-sm-4">
<select name="game_type" class="form-control js_select2">
<option value="">@lang('res.common.select_default')</option>
@foreach(config('platform.game_type') as $key =>$value)
<option value="{{ $key }}" @if($isUpdated && $model->game_type == $key) selected
@endif>{{ $value }}</option>
@endforeach
</select>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label required">点位type</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="type" placeholder="Please enter 点位type" value="{{ $isUpdated?$model->type:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label required">Rebate ratio</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="rate" placeholder="Please enter the rebate ratio" value="{{ $isUpdated?$model->rate:"" }}" @if(!$isUpdated) required @endif>
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
