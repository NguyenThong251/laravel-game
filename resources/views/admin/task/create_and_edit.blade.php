@extends('layouts.baseframe')
@php
    $isUpdated = isset($model->id);
    $title = $isUpdated?"任务修改":"任务Added"
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
                      action="{{ $isUpdated?route('admin.tasks.update',['task' => $model->id]):route('admin.tasks.store') }}"
                      id="form">

                    @csrf

                    @if($isUpdated)
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $model->id }}">
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">任务title</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="title" placeholder="Please enter 任务title" value="{{ $isUpdated?$model->title:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">任务strip件</label>
                        <div class="col-sm-4">
                            <select name="condition_type" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(config('platform.task_condition_types') as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && $model->condition_type == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">任务strip件金额</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="condition_money" placeholder="Please enter 任务strip件金额" value="{{ $isUpdated?$model->condition_money:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group" id="condition-number" style="display: none">
                        <label class="col-sm-2 control-label ">每天完成任务Second-rate数</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="condition_number" placeholder="Please enter 任务strip件Second-rate数" value="{{ $isUpdated?$model->condition_number:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group" id="condition-day" style="display: none">
                        <label class="col-sm-2 control-label ">累计完成任务天数</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="condition_day" placeholder="Please enter strip件天数" value="{{ $isUpdated?$model->condition_day:"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label required">奖励type</label>
                        <div class="col-sm-4">
                            <select name="award_type" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(config('platform.task_award_type') as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && $model->award_type == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="award-money" style="display: none">
                        <label class="col-sm-2 control-label required">奖励额度/金额</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="award_content[money]" placeholder="Please enter 奖励额度/金额" value="{{ $isUpdated?($model->award_content['money'] ?? ""):"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group" id="award-percent" style="display: none">
                        <label class="col-sm-2 control-label required">奖励返点</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="award_content[fd_percent]" placeholder="Please enter 奖励返点百分比" value="{{ $isUpdated?($model->award_content['fd_percent'] ?? ""):"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group" id="award-gametype" style="display: none">
                        <label class="col-sm-2 control-label required">返点type</label>
                        <div class="col-sm-4">
                            {{--
                            <input type="number" class="form-control" name="award_content[game_type]" placeholder="Please enter 奖励返点Game type" value="{{ $isUpdated?$model->award_content:"" }}" @if(!$isUpdated) required @endif>
                            --}}

                            <select name="award_content[game_type]" class="form-control js_select2">
                                <option value="">@lang('res.common.select_default')</option>
                                @foreach(config('platform.game_type') as $key =>$value)
                                    <option value="{{ $key }}" @if($isUpdated && ($model->award_content['game_type'] ?? "") == $key) selected
                                            @endif>{{ $value }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    {{-- 奖励金额 和 奖励Second-rate数 只针对奖励金额 --}}
                    <div class="form-group" id="award-number" style="display: none">
                        <label class="col-sm-2 control-label ">奖励可领取Second-rate数</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="award_content[money_times]" placeholder="Please enter 奖励可领取Second-rate数" value="{{ $isUpdated?($model->award_content['money_times'] ?? ""):"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group" id="award-per-day" style="display: none">
                        <label class="col-sm-2 control-label ">奖励间隔天数</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="award_content[money_per_day]" placeholder="Please enter 奖励间隔天数" value="{{ $isUpdated?($model->award_content['money_per_day'] ?? ""):"" }}" @if(!$isUpdated) required @endif>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label ">Remark information</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="remark" placeholder="Please enter a comment信息" value="{{ $isUpdated?$model->remark:"" }}" @if(!$isUpdated) required @endif>
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

    <script>
        $(function () {

            initView();

            function initView(){
                var conditionTypeSelect = $('[name=condition_type]');
                var conditionTypeValue = conditionTypeSelect.find("option:selected").attr("value");

                // 如果是单笔充值
                if(conditionTypeValue == {{ \App\Models\Task::TYPE_SINGLE_RECHARGE }}){
                    $('#condition-number').show();
                    $('#condition-day').show();
                }else{
                    $('#condition-number').hide();
                    $('#condition-day').hide();
                }

                var awardTypeSelect = $('[name=award_type]');
                var awardTypeValue = awardTypeSelect.find("option:selected").attr("value");

                if(awardTypeValue == {{ \App\Models\Task::AWARD_TYPE_MONEY }} || awardTypeValue == {{ \App\Models\Task::AWARD_TYPE_BORROW }}){
                    $('#award-money').show().find('input[name]').attr("disabled", false);
                    $('#award-percent').hide().find('input[name]').attr("disabled", true);
                    $('#award-gametype').hide().find('select[name]').attr("disabled", true);
                    $('#award-number').show().find('input[name]').attr("disabled", false);
                    $('#award-per-day').show().find('input[name]').attr("disabled", false);
                }else if(awardTypeValue == {{ \App\Models\Task::AWARD_TYPE_FD }}){
                    $('#award-money').hide().find('input[name]').attr("disabled", true);
                    $('#award-percent').show().find('input[name]').attr("disabled", false);
                    $('#award-gametype').show().find('select[name]').attr("disabled", false);
                    $('#award-number').hide().find('input[name]').attr("disabled", true);
                    $('#award-per-day').hide().find('input[name]').attr("disabled", true);
                }
            }

            // 任务strip件改变时
            $('[name=condition_type]').change(function(){
                initView();
            });

            $('[name=award_type]').change(function(){
                initView();
            });
        });

    </script>
@endsection
