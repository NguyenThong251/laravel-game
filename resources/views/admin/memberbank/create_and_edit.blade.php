@extends('layouts.baseframe')
@php
    $isUpdated = isset($model->id);
    $title = $isUpdated?"Member Bank Card Modification":"Member Bank CardAdded"
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
                      action="{{ $isUpdated?route('admin.memberbanks.update',['memberbank' => $model->id]):route('admin.memberbanks.store') }}"
                      id="form">

                    @csrf

                    @if($isUpdated)
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $model->id }}">
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">card number</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="card_no" placeholder="Please enter card number"
                                   value="{{ $isUpdated?$model->card_no:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Bank Type</label>
                        <div class="col-sm-4">
                            <select name="bank_type" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(\App\Models\Bank::getAllBankArray() as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && $model->bank_type == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Reserved mobile number for card application</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="phone" placeholder="Please enter the reserved mobile number for card application"
                                   value="{{ $isUpdated?$model->phone:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Cardholder Name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="owner_name" placeholder="Please enter Cardholder Name"
                                   value="{{ $isUpdated?$model->owner_name:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Bank address</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="bank_address" placeholder="Please enter Bank address"
                                   value="{{ $isUpdated?$model->bank_address:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label ">operate Remarks</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Please enter operation Remarks"
                                   value="{{ $isUpdated?$model->remark:"" }}" @if(!$isUpdated) required @endif readonly>
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
