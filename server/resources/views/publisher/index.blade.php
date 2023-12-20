@extends('layout')

@section('js')
<script>
    $(document).ready(function(){
        var table = $('#myTable').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false, //tùy chỉnh kích thước, phân trang
            "paging": true, "ordering": true, "searching": true,
            "pageLength": 10, 
            "dom": 'Bfrtip', 
            "buttons": [{extend:"copy", text:"Sao chép"}, //custom các button
                        {extend:"csv", text:"Xuất csv"}, 
                        {extend:"excel",text:"Xuất Excel"}, 
                        {extend:"pdf",text:"Xuất PDF"}, 
                        {extend:"print",text:"In"}, 
                        {extend:"colvis",text:"Hiển thị cột"}],
            "language": { search: "Tìm kiếm:" },
            "lengthMenu": [10, 25, 50, 75, 100],
            "ajax": { url: "{{route('publisher.data.table')}}", method: "get", dataType: "json", },
            "columns" : [{ data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'id', render: function(data, type, row){
                            return '<button class="btn btn-warning editBtn" value="' + data + '" data-toggle="modal" data-target="#modal-edit"><i class="nav-icon fa fa-edit"></i></button>'
                                +'<div class="btn-group btn-group-toggle"><button class="btn btn-danger deleteBtn" value="' + data + '"><i class="nav-icon fa fa-trash"></i></button></div>'
                        } },]
        });
        $('#modal-create').on('hidden.bs.modal', function(){
            $('#createFormValidate').removeClass('was-validated');
            $('.create_name_error').text('');
            $('.create_description_error').text('');
        });
        $('#modal-edit').on('hidden.bs.modal', function(){
            $('#updateFormValidate').removeClass('was-validated');
            $('.update_name_error').text('');
            $('.update_description_error').text('');
        });
        //store
        $('#addBtn').click(function(e){
            e.preventDefault();
            var name = $('#storeName').val();
            var description = $('#storeDescription').val();
            $.ajax({
                url: "{{route('publisher.store')}}",
                method: "post",
                data: {
                    "_token":"{{csrf_token()}}",
                    "name":name,
                    "description":description
                }
            }).done(function(res){
                if (res.success) {
                    Swal.fire({ title: res.message, icon: 'success', confirmButtonText: 'OK' });
                    $('#modal-create').modal('hide'); //ẩn model thêm mới
                    $('#storeName').val(''); //clear dữ liệu input sau khi thêm thành công
                    $('#storeDescription').summernote('code', '');
                    table.ajax.reload(); //refresh bảng 
                }
                if(!res.success) {
                    Swal.fire({ title: res.message, icon: 'error', confirmButtonText: 'OK' });
                    return;
                }
            }).fail(function(res){
                $('#createFormValidate').addClass('was-validated');
                $.each(res.responseJSON.errors, function(key, value){
                    $('.create_' + key + '_error').text(value[0]);
                    $('.create_' + key + '_error').text(value[1]);
                });
            });
        });
        //edit
        $('#myTable').on('click', '.editBtn', function(){
            var id = $(this).val();
            $.ajax({
                url: "publisher/edit/" + id,
                method: "get",
            }).done(function(res){
                $('#updateId').val(res.data.id);
                $('#updateName').val(res.data.name);
                $('#updateDescription').summernote('code', res.data.description);
            });
        });
        //update
        $('#updateBtn').click(function(e){
            e.preventDefault();
            var id = $('#updateId').val();
            var name = $('#updateName').val();
            var description = $('#updateDescription').val();
            $.ajax({
                url: "publisher/update/" + id,
                method: "post",
                data:{
                    "_token": "{{csrf_token()}}",
                    "name": name,
                    "description": description
                }
            }).done(function(res){
                if (res.success) {
                    Swal.fire({ title: res.message, icon: 'success', confirmButtonText: 'OK' });
                    $('#modal-edit').modal('hide'); //ẩn model edit
                    table.ajax.reload(); //refresh bảng 
                }
                if(!res.success) {
                    Swal.fire({ title: res.message, icon: 'error', confirmButtonText: 'OK' });
                    return;
                }
            }).fail(function(res){
                $('#updateFormValidate').addClass('was-validated');
                $.each(res.responseJSON.errors, function(key, value){
                    $('.update_' + key + '_error').text(value[0]);
                    $('.update_' + key + '_error').text(value[1]);
                });
            });
        });
        //delete
        $('#myTable').on('click', '.deleteBtn', function(){
            var id = $(this).val();
            Swal.fire({
                title: 'Bạn chắc chắn chứ?',
                text: 'Đừng lo, bạn vẫn có thể khôi phục lại dữ liệu đã xóa!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ'
            }).then(result => {
                if(result.value){
                    $.ajax({
                        url: "publisher/destroy/" + id,
                        method: "post",
                        data:{"_token" : "{{csrf_token()}}",}
                    }).done(function(res){
                        if (res.success) {
                            Swal.fire({ title: res.message, icon: 'success', confirmButtonText: 'OK' });
                            table.ajax.reload();
                        }
                    });
                }
            });
            
        });
        $(function () {
            // create description
            $('#storeDescription').summernote();
            // edit description
            $('#updateDescription').summernote();
        });
    });
</script>
@endsection

@section('content')
<!-- Import File Excel -->
<div class="modal fade" id="modal-import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chọn file Excel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('category.import')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputFile">File Excel</label>
                        <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="file_excel" accept=".xls, .xlsx" class="custom-file-input" >
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            <div class="text-danger create_avatar_error"></div>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Them sach -->
<div class="modal fade" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm nhà xuất bản</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="createFormValidate">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputName">Tên</label>
                        <input type="text" id="storeName" class="form-control" required>
                        <div class="text-danger create_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputName">Mô tả</label>
                        <textarea id="storeDescription" cols="30" rows="10"></textarea>
                        <div class="text-danger create_description_error"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="addBtn">Lưu</button>
                </div>
           </form>
        </div>
    </div>
</div>
<!-- Sua sach -->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sửa nhà xuất bản</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="updateFormValidate">
                <input type="text" id="updateId" hidden>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputName">Tên</label>
                        <input id="updateName" type="text"  class="form-control" required>
                        <div class="text-danger update_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputName">Mô tả</label>
                        <textarea id="updateDescription" cols="30" rows="10"></textarea>
                        <div class="text-danger update_description_error"></div>
                    </div>  
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateBtn">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách nhà xuất bản</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#modal-import">
                        <i class="nav-icon fa fa-plus"></i> Import
                    </button>
                    <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-create">
                        <i class="nav-icon fa fa-plus"></i> Thêm
                    </button>
                    <a href="{{route('publisher.trash')}}" class="btn btn-warning">
                        <i class="nav-icon fa fa-list"></i> Danh sách đã xóa
                    </a>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách nhà xuất bản</h3>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tên</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection