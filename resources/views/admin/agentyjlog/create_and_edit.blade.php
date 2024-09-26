@extends('layouts.baseframe')
@php
$isUpdated = isset($model->id);
$title = $isUpdated?"Agent Commission Record Modification":"Agent Commission RecordAdded"
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
                action="{{ $isUpdated?route('admin.agentyjlogs.update',['agentyjlog' => $model->id]):route('admin.agentyjlogs.store') }}"
                id="form">

                @csrf

                @if($isUpdated)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $model->id }}">
                @endif

                <div class="form-group">
<label class="col-sm-2 control-label ">Proxy ID</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="agent_id" placeholder="Please enter Proxy ID" value="{{ $isUpdated?$model->agent_id:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label required">Profit Amount</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="yl_money" placeholder="Please enter Profit Amount" value="{{ $isUpdated?$model->yl_money:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label required">commission</label>
<div class="col-sm-4">
<input type="number" class="form-control" name="money" placeholder="Please enter commission" value="{{ $isUpdated?$model->money:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">Last commission month</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="last_month" placeholder="Please enter Last commission month" value="{{ $isUpdated?$model->last_month:"" }}" @if(!$isUpdated) required @endif>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label ">operate Remarks</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="remark" placeholder="Please enter operation Remarks" value="{{ $isUpdated?$model->remark:"" }}" @if(!$isUpdated) required @endif>
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
