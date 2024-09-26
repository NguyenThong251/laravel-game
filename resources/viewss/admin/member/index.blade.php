@extends('layouts.admin')

@section('content')
    <h1>Danh sách thành viên</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Hoạt động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->status }}</td>
                    <td>
                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary">Chỉnh sửa</a>
                        <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Không có thành viên nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $data->links() }} <!-- Hiển thị phân trang -->
@endsection