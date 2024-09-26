@extends('layouts.baseframe')
@php
$isUpdated = isset($model->id);
$title = $isUpdated?"Member Amount Log Modified":"Member Amount LogAdded"
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
                action="{{ $isUpdated?route('admin.membermoneylogs.update',['memberMoneyLog' => $model->id]):route('admin.membermoneylogs.store') }}"
                id="form">

                @csrf

                @if($isUpdated)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $model->id }}">
                @endif

                <div class="form-group">
                    <label class="col-sm-2 control-label ">Member ID</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="member_id" placeholder="Please enter Member ID"
                            value="{{ $isUpdated?$model->member_id:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">Administrator ID</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="user_id" placeholder="Please enter your administrator ID"
                            value="{{ $isUpdated?$model->user_id:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label required">operating amount</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="money" placeholder="Please enter the operating amount"
                            value="{{ $isUpdated?$model->money:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">Amount before operation</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="money_before" placeholder="Please enter the Amount before operation"
                            value="{{ $isUpdated?$model->money_before:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">Amount after operation</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="money_after" placeholder="Please enter the Amount after operation"
                            value="{{ $isUpdated?$model->money_after:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">Amount Field Type</label>
                    <div class="col-sm-4">
                        <select name="money_type" class="form-control js_select2">
                            <option value="">@lang('res.common.select_default')</option>
                            @foreach(config('platform.member_money_type') as $key =>$value)
                            <option value="{{ $key }}" @if($isUpdated && $model->money_type == $key) selected
                                @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label required">Quantity Type</label>
                    <div class="col-sm-4">
                        @foreach(config('platform.money_number_type') as $k => $v)
                        <label class="lyear-radio radio-inline radio-primary"><input type="radio" value="{{ $k }}"
                                name="number_type" @if($isUpdated && $model->number_type === $k) checked @endif >
                            <span>{{ $v }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">Amount change type</label>
                    <div class="col-sm-4">
                        <select name="operate_type" class="form-control js_select2">
                            <option value="">@lang('res.common.select_default')</option>
                            @foreach(config('platform.member_money_operate_type') as $key =>$value)
                            <option value="{{ $key }}" @if($isUpdated && $model->operate_type == $key) selected
                                @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">operate Description</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="description" placeholder="Please enter an operation Description"
                            value="{{ $isUpdated?$model->description:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label ">operate Remarks</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="remark" placeholder="Please enter operation Remarks"
                            value="{{ $isUpdated?$model->remark:"" }}" @if(!$isUpdated) required @endif>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" data-operate="ajax-submit" type="button">Save content</button>
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
