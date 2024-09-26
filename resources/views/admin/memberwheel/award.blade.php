@extends('layouts.baseframe')

@section('title', 'Wheel Prize Setting')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Wheel Prize Setting</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.memberwheel.post_award') }}" method="post" id="searchForm" name="searchForm"
                  class="form-horizontal">
                <div class="card-toolbar clearfix">
                    <div class="toolbar-btn-action">
                        <a id="add-btn" class="btn btn-label btn-primary m-r-5" href="javascript:;">
                            <label><i class="mdi mdi-plus"></i></label>Added
                        </a>

                        <a class="btn btn-label btn-info" data-operate="ajax-submit">
                            <label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label> keep
                        </a>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row p-15">
                        <table id="table" class="table table-bordered table-hover text-center">
                            <thead>
                            <tr>
                                <td width="10%">Deposit amount on the day</td>
                                <td width="15%">Effective turnover (multiple)</td>
                                <td width="10%">Second-rate number of turntable</td>
                                <td width="10%">Whether to enable</td>
                                <td width="10%">operate</td>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data as $item)
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" name="deposit[]" placeholder="Please enter the Deposit amount on the day"
                                               value="{{ $item['deposit'] ?? '' }}" />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="valid_num[]" placeholder="Please enter the number of times the effective turnover is the deposit amount"
                                               value="{{ $item['valid_num'] ?? '' }}" />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="Second-rate number of turntable" readonly
                                               value="{{ $item['times'] ?? '' }}" />
                                    </td>
                                    <td class="switch-col">
                                        <label class="lyear-switch switch-solid switch-primary">
                                            <input type="checkbox" name="is_open[]" value="{{ $item['is_open'] }}" @if($item['is_open']) checked
                                                    @endif>
                                            @if(!$item['is_open'])
                                                <input type="hidden" name="is_open[]" value="{{ $item['is_open'] }}">
                                            @endif
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="delete-btn btn btn-danger btn-xs">
                                            delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section("footer-js")
    <script>
        $(function(){
            $(document).on('click', '.delete-btn', function () {
                $(this).parents('tr').remove();
            });

            $('#add-btn').click(function () {
                // 获取 table 中最后一个td
                var tbody = $('#table').find('tbody');
                tbody.append(
                    '<tr><td><input type="number" class="form-control" name="deposit[]" placeholder="Please enter the Deposit amount on the day" value="" /></td>' +
                    '<td><input type="number" class="form-control" name="valid_num[]" placeholder="Please enter the number of times the effective turnover is the deposit amount" value="" /></td>' +
                    '<td><input type="number" class="form-control" placeholder="Second-rate number of turntable" readonly value="1" /></td>' +
                    '<td><label class="lyear-switch switch-solid switch-primary"><input type="checkbox" name="is_open[]" value="1" checked><span></span></label></td>' +
                    '<td><a href="javascript:;" class="delete-btn btn btn-danger btn-xs">delete</a></td></tr>');
            });

        })
    </script>
@endsection