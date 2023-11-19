@extends('layout')

@section('content')

@if(session('errorMsg'))
    <script>
    Swal.fire({
        title: '{{session('errorMsg')}}',
        icon: 'error',
        confirmButtonText: 'OK'
    })
    </script>
@endif

@if(session('successMsg'))
    <script>
    Swal.fire({
        title: '{{session('successMsg')}}',
        icon: 'success',
        confirmButtonText: 'OK'
    })
    </script>
@endif

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="m-n2">
                    <div class="btn-group" role="group">
                        <a href="#">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="btnradio1">Import Excel</label>
                        </a>

                        <a href="#">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                            <label class="btn btn-outline-primary" for="btnradio2">Export Excel</label>
                        </a>

                        <a href="#">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                            <label class="btn btn-outline-primary" for="btnradio3">Export PDF</label>
                        </a>
                        <a href="">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off">
                            <label class="btn btn-outline-primary" for="btnradio4">Refresh</label>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="m-n2">
                    <a href="{{route('admin.index')}}">
                        <button type="button" class="btn btn-warning m-2"><i class="fa fa-list me-2"></i>Danh sách</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">All Admin</h6>
            <a href="">Show All</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col"><input class="form-check-input" type="checkbox"></th>
                        <th scope="col">ID</th>
                        <th scope="col">Tên</th>
                        <th scope="col">Email</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trash as $item)
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>   
                                <a type="button" href="{{route('admin.untrash', $item->id)}}" class="btn btn-rectangle btn-info m-2"><i class="fa fa-trash-restore"></i></a>
                            </td>  
                        </tr>
                    @empty
                        <td colspan=5><p>Không có dữ liệu!</p></td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-4 mt-3 ">
    </div>
</div>
@endsection