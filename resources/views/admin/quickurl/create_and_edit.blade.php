@extends('layouts.baseframe')
@php
    $isUpdated = isset($model->id);
    $title = $isUpdated?"页面路由修改":"页面路由Added"
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
                      action="{{ $isUpdated?route('admin.quickurls.update',['quickurl' => $model->id]):route('admin.quickurls.store') }}"
                      id="form">

                    @csrf

                    @if($isUpdated)
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $model->id }}">
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">路由标识（唯一）</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" placeholder="Please enter 路由标识"
                                   value="{{ $isUpdated?$model->name:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">路由name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="title" placeholder="Please enter 路由name"
                                   value="{{ $isUpdated?$model->title:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">描述</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="desc" placeholder="Please enter 描述"
                                   value="{{ $isUpdated?$model->desc:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">路由type</label>
                        <div class="col-sm-4">
                            <select name="type" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(config('platform.quick_url_type') as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && $model->type == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">路由地址</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="url" placeholder="Please enter 路由地址"
                                   value="{{ $isUpdated?$model->url:"" }}" @if(!$isUpdated) required @endif>
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


        });

    </script>
@endsection
