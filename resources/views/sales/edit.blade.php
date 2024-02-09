@extends('layouts.app')

@section('content')
    @csrf
    <div class="container">
        <div class="titles p-1">
            <h5 class="text-center">Edit Sale Bill</h5>
        </div>
        <hr class="mt-0">
        <div class="row col-md-12">
            <div class="d-flex col-md-5 col-sm-12 mb-4">
                <label for="category_name">INVOICE_NO:</label>
                <input type="text" class="form-control ml-1"
                       id="invoice_number" name="invoice_number"
                       value="{{ $editSale ? old('invoice_number', $editSale->invoice_number) : old('invoice_number') }}"
                       required readonly>
            </div>
            <div class="d-flex col-md-2"></div>
            <div class="d-flex col-md-5 col-sm-12 mb-4">
                <label for="invoice_date">INVOICE_DATE:</label>
                <input type="date" class="form-control ml-1"
                       id="invoice_date" name="invoice_date"
                       value="{{ $editSale ? old('invoice_date', $editSale->invoice_date) : old('invoice_date') }}"
                       required>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5>Customer Information: </h5>
                <div class="row col-md-12 mb-2">
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" class="form-control"
                               id="customer_name" name="customer_name"
                               value="{{ $editSale ? old('customer_name', $editSale->customer_name) : old('customer_name') }}"
                               required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_email">Customer Email:</label>
                        <input type="text" class="form-control"
                               id="customer_email" name="customer_email"
                               value="{{ $editSale ? old('customer_email', $editSale->customer_email) : old('customer_email') }}"
                               required>
                    </div>
                </div>
                <div class="row col-md-12 mb-2">
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_phone">Customer Phone Number:</label>
                        <input type="text" class="form-control"
                               id="customer_phone" name="customer_phone"
                               value="{{ $editSale ? old('customer_phone', $editSale->customer_phone) : old('customer_phone') }}"
                               required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="customer_state">Customer State:</label>
                        <select type="text" class="form-control select2 w-100p"
                                id="customer_state" name="customer_state" required>
                            <option value="">Select a state</option>
                            @foreach($indiaStates as $state)
                                <option
                                    value="{{ $state }}" {{ (old('customer_state', $editSale ? $editSale->customer_state : '') == $state) ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between mb-2">
                        <div class=""><h5>Product Sales Information: </h5></div>
                        <div class="">
                            <button type="button"
                                    class="btn btn-primary btn-sm px-4 float-left"
                                    id="appendButton">
                                Add New Row
                            </button>
                        </div>
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
                                        <select class="form-control product_name select2" data-id="0" id="product_name_{{ $index }}" style="width: 250px !important;" name="sales_products[{{ $index }}][product_name]" onchange="updateRowPrice(this)" required="">
                                            @foreach($products as $product)
                                                <option data-rate="{{ $product->rate }}" data-gst="{{ $product->gst }}" data-category="{{ $product->category->category_name }}" value="{{$product->id}}" {{ (old('product_name', $editSale ? $salesProduct->product_id : '') == $product->id) ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group w-120">
                                            <label for="rate_{{ $index }}" id="rate_label_{{ $index }}" class="input-group-text bg-white">₹</label>
                                            <input type="number" step="any" class="form-control bg-white rate" id="rate_{{ $index }}" name="sales_products[{{ $index }}][rate]" value="{{ $editSale ? old('rate', optional($products->firstWhere('id', $salesProduct->product_id))->rate) : old('rate') }}" readonly="">
                                        </div>
                                    </td>
                                    <td><input type="number" step="any" class="form-control quantity w-100" id="quantity_{{ $index }}" min="0" onchange="amountCalculation();" name="sales_products[{{ $index }}][quantity]" value="{{ $editSale ? old('quantity', $salesProduct->qty) : old('quantity') }}" placeholder="0" required=""></td>
                                    <td>
                                        <div class="input-group w-150">
                                            <label for="gst_{{ $index }}" id="gst_label_{{ $index }}" class="input-group-text bg-white">{{ $editSale ? optional($products->firstWhere('id', $salesProduct->product_id))->gst : '' }}%</label>
                                            <input type="number" step="any" id="gst_{{ $index }}" class="form-control gst bg-white" name="sales_products[{{ $index }}][gst]" value="" readonly="" required="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group w-150">
                                            <label for="total_{{ $index }}" id="total_label_{{ $index }}" class="input-group-text bg-white">₹</label>
                                            <input type="number" step="any" class="form-control total bg-white" id="total_{{ $index }}" name="sales_products[{{ $index }}][total]" value="{{ $editSale ? old('total', $salesProduct->total) : old('total') }}" required="" readonly="">
                                        </div>
                                    </td>
                                    <td><button type="button" class="btn btn-light text-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td></tr>
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
                        id="resetBtn" onclick="resetForm()">RESET
                </button>
                <button type="button"
                        class="btn btn-light btn-sm px-5 border-dark"
                        id="updateBtn" onclick="updateSales()">UPDATE
                </button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            amountCalculation();
            $(".select2").select2({
                theme: "classic"
            });
        });
        var index = document.getElementById("appendInputContent").rows.length;
        $("#appendButton").click(function () {
            var appendInputContent = `<tr class='appendedDivContent'>
            <td>
            <input class="form-control bg-white row_no" style="width: 45px !important;" value="` + (index + 1) + `" readonly>
            </td>
            <td>
                <select class="form-control product_name select2" data-id="` + index + `" id="product_name_` + index + `" style="width: 250px !important;" name="sales_products[` + index + `][product_name]" onchange="updateRowPrice(this)" required>
                    <option></option>
                    @foreach($products as $product)
                        <option data-rate="{{ $product->rate }}" data-gst="{{ $product->gst }}" data-category="{{ $product->category->category_name }}" value="{{$product->id}}">{{ $product->product_name }}</option>
                    @endforeach
            </select>
        </td>
        <td>
            <div class="input-group w-120">
                  <label for="rate_` + index + `" id="rate_label_` + index + `" class="input-group-text bg-white">₹</label>
                      <input type="number" step="any" class="form-control bg-white rate" id="rate_` + index + `" name="sales_products[` + index + `][rate]" value="" readonly>
                </div>
            </td>
            <td><input type="number" step="any" class="form-control quantity w-100" id="quantity_` + index + `" min="0" onchange="amountCalculation();" name="sales_products[` + index + `][quantity]" value="" placeholder="0" required></td>
            <td>
                <div class="input-group w-150">
                  <label for="gst_` + index + `" id="gst_label_` + index + `" class="input-group-text bg-white">%</label>
                  <input type="number" step="any" id="gst_` + index + `" class="form-control gst bg-white" name="sales_products[` + index + `][gst]" value="" readonly required>
                </div>
            </td>
            <td>
                <div class="input-group w-150">
                  <label for="total_` + index + `" id="total_label_` + index + `" class="input-group-text bg-white">₹</label>
                  <input type="number" step="any" class="form-control total bg-white" id="total_` + index + `" name="sales_products[` + index + `][total]" value="" required readonly>
                </div>
            </td>
            <td><button type="button" class="btn btn-light text-danger" onclick="removeRow(this)"><i class="fa fa-trash" ></i></button></td></tr>`;

            $('#appendInputContent').append(appendInputContent);
            index++;
            $(".select2").select2({
                theme: "classic"
            });
        });

        function removeRow(thiss) {
            $(thiss).closest('.appendedDivContent').remove();
            resetRowNo();
            amountCalculation();
        }

        function updateRowPrice(thiss) {
            var selectedOption = thiss.options[thiss.selectedIndex];
            var rate = selectedOption.getAttribute('data-rate');
            var gst = selectedOption.getAttribute('data-gst');
            var index = thiss.id.split('_')[2];

            var currentRowIndex = $(thiss).closest('tr').index();

            var isOptionSelected = false;

            $(".product_name").each(function () {
                var rowIndex = $(this).closest('tr').index();
                if (this.value === thiss.value && rowIndex !== currentRowIndex) {
                    isOptionSelected = true;
                    return false;
                }
            });

            if (isOptionSelected) {
                alert("The product is already listed in another row.");
                clearRowData(thiss.getAttribute("data-id"));
            } else {
                $('#rate_' + index).val(rate);
                $('#gst_label_' + index).text((gst ?? '') + "%");
                amountCalculation();
            }
        }

        function clearRowData(rowIndex) {
            $("#product_name_" + rowIndex).val("");
            $("#rate_" + rowIndex).val(0);
            $("#quantity_" + rowIndex).val(0);
            $("#gst_label_" + rowIndex).text("%");
            amountCalculation();
        }

        function amountCalculation() {
            $(".quantity").each(function () {
                if (!$(this).val()) {
                    $(this).val(0);
                }
                if (!$(this).closest('tr').find('.rate').val()) {
                    $(this).closest('tr').find('.rate').val(0);
                }

                let price = $(this).closest('tr').find('.rate').val();
                let gstPercentage = parseFloat($(this).closest('tr').find('.product_name option:selected').attr('data-gst'));
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

        function isValuePresent(value) {
            if (typeof value === 'string') {
                return value.trim() !== '';
            } else {
                return value !== undefined && value !== null;
            }
        }

        function validateFormData(formData) {
            if (
                isValuePresent(formData.invoice_number) &&
                isValuePresent(formData.invoice_date) &&
                isValuePresent(formData.customer_name) &&
                isValuePresent(formData.customer_email) &&
                isValuePresent(formData.customer_phone) &&
                isValuePresent(formData.customer_state) &&
                isValuePresent(formData.total_cost) &&
                isValuePresent(formData.gst_percentage) &&
                isValuePresent(formData.gst_amount) &&
                formData.sales_products.length > 0
            ) {
                return true;
            } else {
                return false;
            }
        }

        function updateSales() {
            var formData = {
                invoice_number: $('#invoice_number').val(),
                invoice_date: $('#invoice_date').val(),
                customer_name: $('#customer_name').val(),
                customer_email: $('#customer_email').val(),
                customer_phone: $('#customer_phone').val(),
                customer_state: $('#customer_state').val(),
                total_cost: $('#total_amount').val(),
                gst_percentage: (($('#gst_amount').val() / $('#total_amount').val()) * 100).toFixed(2),
                gst_amount: $('#gst_amount').val(),
                sales_products: getSalesProductsData()
            };

            if (validateFormData(formData)) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to save this sale?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, save it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'PUT',
                            url: '{{ route('sales.update', $editSale->id) }}',
                            data: formData,
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Sale successfully updated.",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(function () {
                                    window.location.replace('{{ route('sales.index') }}');
                                }, 1500);
                            },
                            error: function (xhr, status, error) {
                                alert(JSON.parse(xhr.responseText).message);
                            }
                        });
                    } else {
                        Swal.fire('Cancelled', 'Your sale is not updated.', 'info');
                    }
                });
            } else {
                alert('Please fill in all required fields before submitting the form.');
            }
        }

        function getSalesProductsData() {
            var salesProducts = [];

            $('.appendedDivContent').each(function () {
                var product_name = $(this).find('.product_name').val();

                if (product_name !== null && product_name !== "") {
                    var productData = {
                        product_id: product_name,
                        qty: $(this).find('.quantity').val(),
                        total: $(this).find('.total').val(),
                    };

                    salesProducts.push(productData);
                }
            });

            return salesProducts;
        }

        function resetForm() {
            $('#invoice_number').val('');
            $('#invoice_date').val('');
            $('#customer_name').val('');
            $('#customer_email').val('');
            $('#customer_phone').val('');
            $('#customer_state').val('');
            $('#product_cost').val('');
            $('#total_amount').val('');
            $('#gst_amount').val('');
            $('.appendedDivContent').html("");
        }

        function resetRowNo(){
            $(".row_no").each(function(index) {
                $(this).val(index + 1);
            });
        }

    </script>
@endsection
