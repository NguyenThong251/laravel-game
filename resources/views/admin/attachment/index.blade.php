@extends('layouts.baseframe')

@section('title', 'Attachment List')

@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h4>Attachment List</h4>
            <ul class="card-actions">
                <li>
                    <button type="button" data-toggle="collapse" href="#searchContent" aria-expanded="false"
                        aria-controls="searchContent">
                        <i class="mdi mdi-chevron-double-down"></i> fold
                    </button>
                </li>
                <li>
                    <button type="button" onclick="javascript:window.location.reload()">
                        <i class="mdi mdi-refresh"></i> refresh
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
                    //'name' => ['name' => 'username','type' => 'text'],
                    //'type' => ['name' => 'Log type','type' => 'select','data' => \App\Models\AdminLog::$logTypeMap],
                    'created_at' => ['name' => 'Creation time','type' => 'datetime']
                    ]
                    ])

                    <div class="col-lg-2 col-sm-2">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">search</button>&nbsp;
                            <button type="reset" class="btn btn-warning"
                                onclick="document.searchForm.reset()">Reset</button>&nbsp;
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-toolbar clearfix">
            <div class="toolbar-btn-action">
                {{-- <a class="btn btn-primary m-r-5" href="{{ route("admin.adminlogs.create") }}"><i
                    class="mdi mdi-plus"></i> Added</a> --}}
                <a class="btn btn-danger" id="batchDelete" data-operate="delete" data-url="/admin/attachments/ids">
                    <i class="mdi mdi-window-close"></i> delete
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
                            <th style="min-width: 90px">Uploader Account</th>
                            <th>Uploader IP</th>
                            <th>Original name</th>
                            <th>MIMEtype</th>
                            <th>File category</th>
                            <th>File size</th>
                            <th>File address</th>                                                    
                            <th>Creation time</th>
                            <th>operate</th>
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
                            <td>{{ $item->owner->name ?? '' }}</td>
                            <td>{{ $item->ip }}</td>
                            <td>{{ $item->original_name }}</td>
                            <td>{{ $item->mime_type }}</td>
                            <td>{{ $item->file_type_text }}</td>
                            <td>{{ $item->size }} kb</td>
                            <td><a href="{{ $item->file_url }}" target="_blank">Click Preview</a></td>
                            <td width="100">{{ $item->created_at }}</td>
                            <td width="100">
                                <div class="btn-group">
                                    {{-- <a class="btn btn-xs btn-default" href="{{ route('admin.adminlogs.edit',['adminlog' => $item->id]) }}"
                                    title="" data-toggle="tooltip"
                                    data-original-title="edit"><i class="mdi mdi-pencil"></i></a> --}}

                                    <a class="btn btn-xs btn-default" href="javascript:;" data-operate="show-page"
                                    data-toggle="tooltip" data-original-title="Details"
                                    data-url="{{ route('admin.attachments.show', ['attachment' => $item->id]) }}">
                                        <i class="mdi mdi-file-document-box"></i>
                                    </a>

                                    <a class="btn btn-xs btn-default" href="javascript:;" data-operate="delete"
                                        data-toggle="tooltip" data-original-title="delete"
                                        data-url="{{ route('admin.attachments.destroy', ['attachment' => $item->id]) }}">
                                        <i class="mdi mdi-window-close"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="clearfix">
                <div class="pull-left">
                    <p>Total <strong style="color: red">{{ $data->total() }}</strong> strip</p>
                </div>
                <div class="pull-right">
                    {!! $data->appends($params)->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("footer-js")
<script>
    //日期时间范围
    laydate.render({
        elem: '#created_at',
        type: 'datetime',
        theme: "#33cabb",
        range: "~"
    });

</script>
@endsection
