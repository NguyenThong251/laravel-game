@extends('layouts.baseframe')

@section('title', $_title)
@section('content')

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $_title }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>@lang('res.member.member_apis.api_title')</th>
                            <th>@lang('res.member.member_apis.money')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->api->api_title ?? trans('res.member.member_apis.null') }}</td>
                                <td>
                                    <a href="javascript:;" class="btn btn-danger btn-xs fresh-money"
                                       data-url="{{ route('admin.member.refresh_api',['member_api' => $item]) }}"
                                       data-toggle="tooltip" data-original-title="@lang('res.member.member_apis.refresh')">
                                        <i class="mdi mdi-refresh"></i>
                                        <span>{{ $item->money }}</span>
                                    </a>

                                    <a href="javascript:;" class="btn btn-info btn-xs recycle-money"
                                       data-url="{{ route('admin.member.recycle_api',['member_api' => $item]) }}"
                                       data-toggle="tooltip" data-original-title="@lang('res.member.member_apis.recycle')">
                                        <span>@lang('res.member.member_apis.recycle')</span>       
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("footer-js")
    <script>

        $('.fresh-money').click(function(){
            var _this = $(this);
            _this.attr("disabled", true);

            if(!_this.data('url')) return;

            $.ajax({
                url: _this.data('url'),
                method:'post',
                success:function(res){
                    _this.attr("disabled", false);

                    if(res.code == 200 && res.data) _this.find('span').html(res.data)
                    else $.utils.layerError(res.message)
                },
                error:function(err){
                    _this.attr("disabled", false);

                }
            });
        });

        $('.recycle-money').click(function(){
            var _this = $(this);
            _this.attr("disabled", true);

            if(!_this.data('url')) return;

            $.ajax({
                url: _this.data('url'),
                method:'post',
                success:function(res){
                    _this.attr("disabled", false);

                    if(res.code == 200 && res.data >= 0){
                        _this.siblings('.fresh-money').find('span').html(parseFloat(res.data).toFixed(2));
                        $.utils.layerSuccess($.utils.getLangs('success_message'));
                    }
                    else $.utils.layerError(res.message);
                },
                error:function(err){
                    _this.attr("disabled", false);

                }
            });
        })
    </script>
@endsection