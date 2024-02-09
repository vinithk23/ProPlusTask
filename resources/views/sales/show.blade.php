@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="titles p-1">
            <h5 class="text-center">View Sale Bill</h5>
        </div>
        <hr class="mt-0">
        <div class="row col-md-12">
            <div class="d-flex col-md-5 col-sm-12 mb-4">
                <label for="category_name">INVOICE_NO:</label>
                <input type="text" class="form-control bg-white ml-1"
                       id="invoice_number" name="invoice_number"
                       value="{{ $editSale ? $editSale->invoice_number : '' }}"
                       required readonly>
            </div>
            <div class="d-flex col-md-2"></div>
            <div class="d-flex col-md-5 col-sm-12 mb-4">
                <label for="invoice_date">INVOICE_DATE:</label>
                <input type="date" class="form-control bg-white ml-1"
                       id="invoice_date" name="invoice_date"
                       value="{{ $editSale ? $editSale->invoice_date : '' }}"
                       required readonly>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5>Customer Information: </h5>
                <div class="row col-md-12 mb-2">
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" class="form-control bg-white"
                               id="customer_name" name="customer_name"
                               value="{{ $editSale ? $editSale->customer_name : '' }}"
                               required readonly>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_email">Customer Email:</label>
                        <input type="text" class="form-control bg-white"
                               id="customer_email" name="customer_email"
                               value="{{ $editSale ? $editSale->customer_email : '' }}"
                               required readonly>
                    </div>
                </div>
                <div class="row col-md-12 mb-2">
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_phone">Customer Phone Number:</label>
                        <input type="text" class="form-control bg-white"
                               id="customer_phone" name="customer_phone"
                               value="{{ $editSale ? $editSale->customer_phone : '' }}"
                               required readonly>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_state">State:</label>
                        <input type="text" class="form-control bg-white select2 w-100p"
                                id="customer_state" name="customer_state" value="{{ $editSale ? $editSale->customer_state : '' }}" required readonly>
                        </input>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between mb-2">
                        <div class=""><h5>Product Sales Information: </h5></div>
                    </div>
                    <div class="table-responsive pl-0 pr-0">
                        <table class="table nowrap text-center table-striped f-100">
                            <tbody id="appendInputContent">
                            @foreach($editSale->salesProducts as $index => $salesProduct)
                                <tr class="appendedDivContent">
                                    <td>
                                        <input class="form-control bg-white row_no" style="width: 45px !important;" value="{{ $index + 1 }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control product_name bg-white" data-id="0" data-gst="{{ $salesProduct->product->gst }}" id="product_name_{{ $index }}" style="width: 250px !important;" name="sales_products[{{ $index }}][product_name]" value="{{ $editSale ? $salesProduct->product->product_name : '' }}" readonly>
                                    </td>
                                    <td>
                                        <div class="input-group w-120">
                                            <label for="rate_{{ $index }}" id="rate_label_{{ $index }}" class="input-group-text bg-white">₹</label>
                                            <input type="number" step="any" class="form-control bg-white rate" id="rate_{{ $index }}" name="sales_products[{{ $index }}][rate]" value="{{ $editSale ? $salesProduct->product->rate : 0 }}" readonly="">
                                        </div>
                                    </td>
                                    <td><input type="number" step="any" class="form-control bg-white quantity" id="quantity_{{ $index }}" min="0" onchange="amountCalculation();" name="sales_products[{{ $index }}][quantity]" value="{{ $editSale ? $salesProduct->qty : 0 }}" placeholder="0" readonly></td>
                                    <td>
                                        <div class="input-group w-150">
                                            <label for="gst_{{ $index }}" id="gst_label_{{ $index }}" class="input-group-text bg-white">{{ $editSale ? $salesProduct->product->gst : '' }}%</label>
                                            <input type="number" step="any" id="gst_{{ $index }}" class="form-control gst bg-white" name="sales_products[{{ $index }}][gst]" value="" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group w-150">
                                            <label for="total_{{ $index }}" id="total_label_{{ $index }}" class="input-group-text bg-white">₹</label>
                                            <input type="number" step="any" class="form-control total bg-white" id="total_{{ $index }}" name="sales_products[{{ $index }}][total]" value="{{ $editSale ? old('total', $salesProduct->total) : old('total') }}" readonly="">
                                        </div>
                                    </td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row col-12 mt-5">
            <div class="col-md-6"></div>
            <div class="row col-md-6 col-sm-12">
                <div class="col-md-6 col-sm-6" style="text-align: end; align-self: center;">
                    <b>Product Cost:</b>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <label for="product_cost" id="product_cost_label"
                               class="input-group-text bg-white border-0">₹</label>
                        <input class="form-control border-white p-0" type="text" id="product_cost" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-12 mt-2">
            <div class="col-md-6"></div>
            <div class="row col-md-6 col-sm-12">
                <div class="col-md-6 col-sm-6" style="text-align: end; align-self: center;">
                    <b>Total Gst:</b>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <label for="gst_amount" id="gst_amount_label"
                               class="input-group-text bg-white border-0">₹</label>
                        <input class="form-control border-white p-0" type="text" id="gst_amount" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-12 mt-2">
            <div class="col-md-6"></div>
            <div class="row col-md-6 col-sm-12">
                <div class="col-md-6 col-sm-6 text-brown" style="text-align: end; align-self: center;">
                    <b>Total Sales Value:</b>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <label for="total_amount" id="total_amount_label"
                               class="input-group-text bg-white border-0 text-brown">₹</label>
                        <input class="form-control border-white p-0 text-brown" type="text" id="total_amount" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-12 mt-4">
            <div class="col-md-6"></div>
            <div class="col-md-6 col-sm-12" style="text-align: center;">
                <button type="button"
                        class="btn btn-dark btn-sm px-5"
                        id="backBtn" onclick="window.history.back();">Back
                </button>
                <a href="{{ route('sales.edit', $editSale->id) }}"
                        class="btn btn-light btn-sm px-5 border-dark"
                        id="editBtn">Edit
                </a>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            amountCalculation();
        });

        function amountCalculation() {
            $(".quantity").each(function () {
                if (!$(this).val()) {
                    $(this).val(0);
                }
                if (!$(this).closest('tr').find('.rate').val()) {
                    $(this).closest('tr').find('.rate').val(0);
                }

                let price = $(this).closest('tr').find('.rate').val();
                let gstPercentage = parseFloat($(this).closest('tr').find('.product_name').attr('data-gst'));
                let quantity = $(this).val();
                let total = (parseFloat(price) * parseFloat(quantity)).toFixed(2);

                let gst = (total * (gstPercentage / 100)).toFixed(2);

                $(this).closest('tr').find('.total').val(total);
                $(this).closest('tr').find('.gst').val(gst);
            });

            let product_cost = 0;
            $(".total").each(function () {
                if ($(this).val()) {
                    product_cost += parseFloat($(this).val());
                }
            });

            $('#product_cost').val(product_cost.toFixed(2));

            let gst_amount = 0;
            $(".gst").each(function () {
                if ($(this).val()) {
                    gst_amount += parseFloat($(this).val());
                }
            });

            $('#gst_amount').val(gst_amount.toFixed(2));
            $('#total_amount').val((product_cost + gst_amount).toFixed(2));
        }

    </script>
@endsection
