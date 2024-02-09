@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="container-fluid">
                    <div class="titles d-flex justify-content-between">
                        <div class="mr-auto"><h5 class="card-title">Sales List</h5></div>
                        <a class="btn btn-success" href="{{ route('sales.create') }}">Create Sale</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-2">
                    <table class="table" id="sales_table">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Sale ID</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Customer Phone</th>
                            <th>State</th>
                            <th>Total Cost</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#sales_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'invoice_number', name: 'invoice_number'},
                    {data: 'invoice_date', name: 'invoice_date'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'customer_email', name: 'customer_email'},
                    {data: 'customer_phone', name: 'customer_phone'},
                    {data: 'customer_state', name: 'customer_state'},
                    {data: 'total_cost', name: 'total_cost'},
                    {data: 'action', name: 'action'},
                ],
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', 'print'
                ],
            });
        });
    </script>
@endsection
