<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SalesProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sale::select(['id', 'invoice_number', 'invoice_date', 'customer_name', 'customer_email', 'customer_phone', 'customer_state', 'total_cost'])
                ->orderBy('created_at', 'desc')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">
                    <a href="' . route('sales.show', $data->id) . '" class="btn btn-info">View</a>&nbsp;
                    <a href="' . route('sales.edit', $data->id) . '" class="btn btn-secondary">Edit</a></div>';

                    return $button;
                })
                ->make(true);
        }
        return view('sales.index');
    }

    public function create(Request $request)
    {
        $products = Product::with('category')->get();
        $invoice_no = str_pad(DB::table('sales')->max('id') + 1, 3, '0', STR_PAD_LEFT);

        return view("sales.create", compact('products', 'invoice_no'));
    }

    public function edit($id)
    {
        $products = Product::with('category')->get();
        $editSale = Sale::with('salesProducts')->find($id);

        return view("sales.edit", compact('products', 'editSale'));
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::create($request->all());

            foreach ($request->sales_products as $salesProduct) {
                $salesProduct['sale_id'] = $sale->id;
                SalesProduct::create($salesProduct);
                $product = Product::find($salesProduct['product_id']);
                $purchasedQty = $salesProduct['qty'];

                if ($product->qty >= $purchasedQty) {
                    $product->decrement('qty', $purchasedQty);
                } else {
                    $message = "Insufficient quantity in stock for $product->product_name and maximum limit is $product->qty";
                    DB::rollBack();
                    return response()->json(['message' => $message], 422);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Sale saved successfully'], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            info('Error::Place@SaleController@store - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function update(SaleRequest $request, Sale $sale)
    {
        DB::beginTransaction();
        try {
            $sales = Sale::with('salesProducts')->find($sale->id);
            $sales->update($request->all());

            $requestProductIds = collect($request->sales_products)->pluck('product_id')->all();

            $sales->salesProducts->reject(function ($salesProduct) use ($requestProductIds) {
                if (!in_array($salesProduct->product_id, $requestProductIds)) {
                    Product::find($salesProduct->product_id)->increment('qty', $salesProduct->qty);
                }
            });

            $sales->salesProducts()->delete();

            foreach ($request->sales_products as $salesProduct) {
                $salesProduct['sale_id'] = $sale->id;
                SalesProduct::create($salesProduct);

                $productId = $salesProduct['product_id'];
                $purchasedQty = $salesProduct['qty'];
                $product = Product::find($productId);
                $previousBuyQty = $sales->salesProducts->firstWhere('product_id', $productId)->qty ?? 0;

                $changeInQty = $purchasedQty - $previousBuyQty;
                if ($changeInQty > 0) {
                    if ($product->qty >= $changeInQty) {
                        $product->decrement('qty', $changeInQty);
                    } else {
                        $current_stock = $product->qty+$previousBuyQty;
                        $message = "Insufficient quantity in stock for $product->product_name. Current stock: $current_stock, Requested: $purchasedQty";
                        DB::rollBack();
                        return response()->json(['message' => $message], 422);
                    }
                } elseif ($changeInQty < 0) {
                    $product->increment('qty', abs($changeInQty));
                }
            }
            DB::commit();
            return response()->json(['message' => 'Sale updated successfully'], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            info('Place@SaleController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function show($id)
    {
        $editSale = Sale::with('salesProducts.product')->find($id);
        return view('sales.show', compact('editSale'));
    }
}
