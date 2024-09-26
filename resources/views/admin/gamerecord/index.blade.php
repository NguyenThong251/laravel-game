@extends('layouts.baseframe')

@section('title', $_title)

@section('content')
<div class="col-sm-12">

    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        {{ trans('res.common.lang_notice') }}
    </div>

    <div class="card">
        <div class="card-header">
            <h4>{{ $_title }}</h4>
            <ul class="card-actions">
                <li>
                    <button type="button" data-toggle="collapse" href="#searchContent" aria-expanded="false"
                        aria-controls="searchContent">
                        <i class="mdi mdi-chevron-double-down"></i> @lang('res.btn.collapse')
                    </button>
                </li>
                <li>
                    <button type="button" onclick="javascript:window.location.reload()">
                        <i class="mdi mdi-refresh"></i> @lang('res.btn.refresh')
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body collapse in" id="searchContent" aria-expanded="true">
            <form action="" method="get" id="searchForm" name="searchForm">
                <div class="row">
                    @include('layouts._search_field',
                    [
                    'list' => [
                        'member_lang' => ['name' => trans('res.member.field.lang'),'type' => 'select','data' => config('platform.lang_select')],
                        'api_name' => ['name' => trans('res.game_record.field.api_name'),'type' => 'select','data' => App\Models\Api::query()->getApiNameArray()],
                        'gameType' => ['name' => trans('res.game_record.field.gameType'),'type' => 'select','data' => trans('res.option.game_type')],
                        'created_at' => ['name' => trans('res.common.created_at'),'type' => 'datetime'],
                        'member_id' => ['name' => trans('res.common.member_name'),'type' => 'select','data' => \App\Models\Member::getMemberArray()],
                        'is_fd' => ['name' => trans('res.game_record.field.is_fd'),'type' => 'select','data' => trans('res.option.boolean')],
                        ]
                    ])

                    {{--<input type="hidden" name="member_id" value="{{ $params['member_id'] ?? '' }}">--}}

                    <div class="col-lg-3 col-sm-3">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">@lang('res.btn.search')</button>&nbsp;
                            <button type="reset" class="btn btn-warning"
                                onclick="document.searchForm.reset()">@lang('res.btn.reset')</button>&nbsp;
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-toolbar clearfix">
            <div class="toolbar-btn-action">
                {{-- <a class="btn btn-primary m-r-5" href="{{ route("admin.gamerecords.create") }}"><i
                        class="mdi mdi-plus"></i>
                    Added</a> --}}
                {{-- <a class="btn btn-success m-r-5" href="#!"><i class="mdi mdi-check"></i> Enable</a>
                <a class="btn btn-warning m-r-5" href="#!"><i class="mdi mdi-block-helper"></i> Disable</a> --}}
                <a class="btn btn-danger" id="batchDelete" data-operate="delete" data-url="/admin/gamerecords/ids">
                    <i class="mdi mdi-window-close"></i> @lang('res.btn.delete')
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <label class="lyear-checkbox checkbox-primary">
                                    <input type="checkbox" id="check-all"><span></span>
                                </label>
                            </th>
                            @include('layouts._table_header',['data' => \App\Models\GameRecord::$list_field,'model' => 'game_record'])
                            <th style="min-width: 90px;">@lang('res.game_record.field.shuyinAmount')</th>
                            <th width=100 style="min-width: 100px;">@lang('res.common.updated_at')</th>
                            <th width=100>@lang('res.common.created_at')</th>
                            <th>@lang('res.common.operate')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>
                                <label class="lyear-checkbox checkbox-primary">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"><span></span>
                                </label>
                            </td>
                            @include('layouts._table_body',['data' => \App\Models\GameRecord::$list_field,'item' => $item])
                            <td>
                                @if($item->status == \App\Models\GameRecord::STATUS_COMPLETE)
                                {{ $item->netAmount }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>{{ $item->updated_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    {{-- <a class="btn btn-xs btn-default"
                                        href="{{ route('admin.gamerecords.edit',['gamerecord' => $item->id]) }}" title=""
                                        data-toggle="tooltip" data-original-title="edit"><i
                                            class="mdi mdi-pencil"></i></a> --}}

                                    <a class="btn btn-xs btn-default" href="javascript:;" data-operate="show-page"
                                        data-toggle="tooltip" data-original-title="@lang('res.btn.detail')"
                                        data-url="{{ route('admin.gamerecords.show', ['gamerecord' => $item->id]) }}">
                                    <i class="mdi mdi-file-document-box"></i>
                                    </a>

                                    @if(!app(\App\Services\AgentService::class)->isTraditionalMode() && $item->canSendFd())
                                        <a class="btn btn-xs btn-default" href="javascript:;" data-operate="alert-deal"
                                           data-method="post" data-message="@lang('res.game_record.index.notice_send_fd')"
                                           data-toggle="tooltip" data-original-title="@lang('res.game_record.index.btn_send_fd')"
                                           data-url="{{ route('admin.agentfdmoneylog.handle_record', ['gamerecord' => $item->id]) }}">
                                            <i class="mdi mdi-coin"></i>
                                        </a>
                                    @endif

                                    <a class="btn btn-xs btn-default" href="javascript:;" data-operate="delete"
                                        data-toggle="tooltip" data-original-title="@lang('res.btn.delete')"
                                        data-url="{{ route('admin.gamerecords.destroy', ['gamerecord' => $item->id]) }}">
                                        <i class="mdi mdi-window-close"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <td><strong style="color: red">Bet Explanation</strong></td>
                        <td colspan="12">The winning and losing amount includes the principal, and only the bonus amount is displayed; &nbsp;&nbsp; Actual win or loss = win or loss amount - bet amount (positive number means member wins, negative number means member loses); &nbsp;&nbsp; Effective amount = 0 means the bet is invalid or baccarat has a tie</td>
                    </tfoot>					
                    <tfoot>
                        <td><strong style="color: red">@lang('res.common.sum')</strong></td>
                        <td colspan="6"></td>
                        <td><strong style="color: red">{{ $total_betAmount }}</strong></td>
                        <td><strong style="color: red">{{ $total_validBetAmount }}</strong></td>
                        <td><strong style="color: red">{{ $total_netAmount }}</strong></td>
                        <td colspan="3" style="color: red">Actual win or loss:&nbsp;&nbsp;&nbsp;{{ $total_netAmount - $total_betAmount }}</td>
                    </tfoot>
                </table>
            </div>

            @if($data)
            <div class="clearfix">
                <div class="pull-left">
                    <p>@lang('res.common.total') <strong style="color: red">{{ $data->total() }}</strong> @lang('res.common.count')</p>
                </div>
                <div class="pull-right">
                    {!! $data->appends($params)->render() !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
