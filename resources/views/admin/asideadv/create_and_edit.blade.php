@extends('layouts.baseframe')
@php
    $isUpdated = isset($model->id);
    $title = $isUpdated?"Corner Ads Modified":"Corner AdsAdded"
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
                      action="{{ $isUpdated?route('admin.asideadvs.update',['asideadv' => $model->id]):route('admin.asideadvs.store') }}"
                      id="form">

                    @csrf

                    @if($isUpdated)
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $model->id }}">
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" placeholder="Please enter name"
                                   value="{{ $isUpdated?$model->name:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Group Name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="group" placeholder="Please enter Group Name"
                                   value="{{ $isUpdated?$model->group:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Advertising pictures</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="pic_url" placeholder="Please enter an ad image"
                                   value="{{ $isUpdated?$model->pic_url:"" }}" @if(!$isUpdated) required
                                   @endif readonly>
                        </div>
                    </div>
                    <div class="form-group" id="upload-area">
                        <label class="col-sm-2 control-label">Upload pictures</label>
                        <div class="col-sm-8">
                            <ul class="list-inline clearfix lyear-uploads-pic"
                                data-upload-url="{{ route('attachment.upload',['file_type' => 'pic','category' => 'asideAdv']) }}"
                                data-delete-url="{{ route('attachment.delete') }}"
                                @if($isUpdated) data-image-url="{{ $model->pic_url }}" @endif>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Image Index</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="pic_index" placeholder="Please enter Image Index"
                                   value="{{ $isUpdated?$model->pic_index:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                        <div class="col-sm-4">
                            <div class="help-block">
                                If empty, automatically fill in
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Vertical Position</label>
                        <div class="col-sm-4">
                            @foreach(config('platform.adv_vertical') as $k => $v)
                                <label class="lyear-radio radio-inline radio-primary">
                                    <input type="radio" value="{{ $k }}" name="vertical" @if($isUpdated && $model->vertical === $k) checked @endif >
                                    <span>{{ $v }}</span></label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Horizontal position</label>
                        <div class="col-sm-4">
                            @foreach(config('platform.adv_horizontal') as $k => $v)
                                <label class="lyear-radio radio-inline radio-primary">
                                    <input type="radio" value="{{ $k }}" name="horizontal" @if($isUpdated && $model->horizontal === $k) checked @endif >
                                    <span>{{ $v }}</span></label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Special Effects</label>
                        <div class="col-sm-4">
                            <select name="effect" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(config('platform.adv_effect') as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && $model->effect == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Jump routing</label>
                        <div class="col-sm-4">
                            <select name="url_id" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(\App\Models\QuickUrl::opened()->pluck('title','id')->toArray() as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && $model->url_id == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Remark information</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="remark" placeholder="Please enter a comment信息"
                                   value="{{ $isUpdated?$model->remark:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">@lang('res.about.field.lang')</label>
                        <div class="col-sm-4">
                            <select name="lang" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(config('platform.lang_fields') as $k => $v)
                                    <option value="{{ $k }}" @if($isUpdated && $model->lang == $k) selected
                                            @endif>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Open</label>
                        <div class="col-sm-4">
                            @foreach(config('platform.is_open') as $k => $v)
                                <label class="lyear-radio radio-inline radio-primary">
                                    <input type="radio" value="{{ $k }}" name="is_open" @if($isUpdated && $model->is_open === $k) checked @endif >
                                    <span>{{ $v }}</span></label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Sorting</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="weight" placeholder="Please enter Sorting"
                                   value="{{ $isUpdated?$model->weight:"" }}" @if(!$isUpdated) required @endif>
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
    <script>
        $(function () {

            // 单图上传
            $('.lyear-uploads-pic').imageUpload({
                $callback_input: $('.form-control[name="pic_url"]'),
                showErrorDialog: $.utils.layerError,
                showSuccessDialog: $.utils.layerSuccess
            });


        });

    </script>
@endsection
