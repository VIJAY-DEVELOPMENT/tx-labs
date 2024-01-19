@extends('admin.layouts.app')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="mb-0">{{ isset($title) ? $title : '' }}</h4>
                        <div class="ml-auto">
                            <a href="{{ route('admin.users.create') }}" data-title="User Create"
                                class="btn btn-primary font-weight-bolder btn-sm modal-link">
                                Add
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th width="100px">Action</th>
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
@endsection
@section('script')
    <script src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(document).on("submit", "#user-form", async function(e) {
            e.preventDefault();
            var data = await ajaxDynamicMethod($(this).attr('action'), $(this).attr('method'), generateFormData(this));
            console.warn(data);
            if (data.success) {
                toastrsuccess(data.msg);
                $('#common-modal').modal('hide');
                var table = $('.data-table').DataTable();
                table.ajax.reload(null, false);
            }
        })

        $(document).on("click", ".delete-users", async function(e) {
            e.preventDefault();
            var URL = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    var data = await ajaxDynamicMethod(URL, "DELETE");
                    if (data.success) {
                        toastrsuccess(data.msg);
                        var table = $('.data-table').DataTable();
                        table.ajax.reload(null, false);
                    }
                }
            })
        })    
    </script>
@endsection
