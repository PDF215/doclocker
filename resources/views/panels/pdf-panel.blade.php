@php
    $levelAmount = 'level';

    if (Auth::User()->level() >= 2) {
    $levelAmount = 'levels';
    }
@endphp

<div class="card">
    <div class="card-header @role('admin', true) bg-secondary text-white @endrole">

        Welcome {{ Auth::user()->name }}

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Admin Access
            </span>
        @else
            <span class="pull-right badge badge-warning" style="margin-top:4px">
                User Access
            </span>
            @endrole

    </div>

    <div class="card-body">
        <h2 class="lead">
            All PDF List
        </h2>

        <hr>

        <a href="{{ url('pdf/create') }}" class="text-center btn btn-success mb-3 float-right">Create PDF</a>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover dt-responsive display nowrap"
                   id="pdf-datatable-crud">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Invoice Content</th>
                    <th>Invoice Description</th>
                    <th>Invoice Title</th>
                    <th>Invoice Number</th>
                    <th>PDF Templates</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#pdf-datatable-crud').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('pdf/index') }}",
                type: 'GET',
            },
            columns: [
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'table_content', name: 'table_content'},
                {data: 'table_description', name: 'table_description'},
                {data: 'invoice_title', name: 'invoice_title'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'template', name: 'template'},
                {data: 'action', name: 'action'}
            ]
        });
    });

    $('body').on('click', '.deleteTodo', function () {

        var pdf_id = $(this).data("id");
        if (confirm("Are You sure want to delete !")) {
            $.ajax({
                type: "get",
                url: "{{ url('pdf/delete-pdf') }}" + '/' + pdf_id,
                success: function (data) {
                    var oTable = $('#pdf-datatable-crud').dataTable();
                    oTable.fnDraw(false);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });

</script>
