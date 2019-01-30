@extends('layouts.master')
@section('script')
<script src="{{ asset('js/avatar.js') }}" defer></script>
<script src="{{ asset('js/tooltip.js') }}" defer></script>
<script src="{{ asset('js/circle-progress.min.js') }}" defer></script>
<script src="{{ asset('js/circleProgress.js') }}" defer></script>
@endsection
@section('title','組織成員')
@section('content')
<div class="container">
    @include('organization.company.show')
    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="department-tab" href="{{ route('company.index') }}">子部門</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('company.okr') }}">OKRs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="member-tab" data-toggle="tab" href="#member" role="tab" aria-controls="member"
                aria-selected="false">成員</a>
        </li>
    </ul>
    <div class="row justify-content-md-center">
        <div class="col-sm-10 mt-4">
            <div class="float-right mb-2">
                <form action="{{route('company.member')}}" class="form-inline search-form">
                    <select name="order" class="form-control input-sm mr-2 ml-2">
                        <option value="name_asc">姓名排序</option>
                        <option value="email_asc">信箱排序</option>
                        <option value="department_id_asc">部門度排序</option>
                        <option value="position_asc">職稱排序</option>
                    </select>
                    <button type="submit" value="Submit" class="btn btn-primary">搜索</button>
                </form>
            </div>
            {{ $members->links() }}
            <table class="rwd-table table table-hover">
                <thead>
                    <tr class="bg-primary text-light text-center">
                        <th>追蹤</th>
                        <th>頭像</th>
                        <th>姓名</th>
                        <th>信箱</th>
                        <th>部門</th>
                        <th>職稱</th>
                        <th>權限</th>
                        @can('memberSetting', $company)
                            <th>設定</th>                                
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr class="text-center">
                        <td data-th="追蹤">
                            @if ($member->following())
                            <a href="{{ route('follow.cancel', [get_class($member), $member]) }}" class="text-warning">
                                <i class="fas fa-star" style="font-size: 24px;"></i>
                            </a>
                            @else
                            <a href="{{ route('follow', [get_class($member), $member]) }}" class="text-warning">
                                <i class="far fa-star" style="font-size: 24px;"></i>
                            </a>
                            @endif
                        </td>
                        <td data-th="頭像">
                            <a href="{{ route('user.okr', $member->id) }}" class="u-ml-8 u-mr-8">
                                <img src="{{ $member->getAvatar() }}" alt="" class="avatar-sm text-center bg-white">
                            </a>
                        </td>
                        <td data-th="姓名">{{ $member->name }}</td>
                        <td data-th="信箱">{{ $member->email }}</td>
                        @cannot('memberSetting', $company)
                            <td data-th="部門">{{ $member->department? $member->department->name:$company->name }}</td>
                            <td data-th="職稱">{{ $member->position }}</td>
                            <td data-th="權限">{{ $member->role($company)->name }}</td>
                        @endcannot
                        @can('memberSetting', $company)
                            <td data-th="部門">
                                <select name="department{{ $member->id }}" id="department{{ $member->id }}" class="form-control">
                                    <option value="{{$company->id}}">{{ $company->name }}</option>
                                    @foreach ($departments as $department)
                                    @if ($department->id == $member->department_id)
                                    <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                                    @else
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </td>
                            <td data-th="職稱">
                                <input name="position{{ $member->id }}" type="text" class="form-control" value="{{ $member->position }}">
                            </td>
                            <td data-th="權限">{{ $member->role($company)->name }}</td>
                            <td data-th="設定">
                                <a href="#" onclick="document.getElementById('memberDelete{{ $member->id }}').submit()">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@foreach($members as $member)
<form name="memberDelete{{ $member->id }}" method="POST" id="memberDelete{{ $member->id }}" action="{{ route('company.member.destroy', $member ) }}">
    @csrf
    {{ method_field('PATCH') }}
</form>
@endforeach
@endsection
