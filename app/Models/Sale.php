<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_state',
        'total_cost',
        'gst_percentage',
        'gst_amount'
        ];

        public function salesProducts()
        {
            return $this->hasMany(SalesProduct::class, 'sale_id');
        }
}
