<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = optional($this->route('sale'))->id;
        return [
            'invoice_number' => 'required|string|unique:sales,invoice_number,' . $id . ',id',
            'invoice_date' => 'required|date',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|numeric|digits:10',
            'customer_state' => 'required|string',
            'total_cost' => 'required|numeric',
            'gst_percentage' => 'required|numeric',
            'gst_amount' => 'required|numeric',
            'sales_products' => 'required|array',
            'sales_products.*.product_id' => 'required|numeric',
            'sales_products.*.qty' => 'required|numeric',
            'sales_products.*.total' => 'required|numeric',
        ];
    }
}
