<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Productモデルを使用
use App\Models\Sale; // Saleモデルを使用
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);

        DB::beginTransaction();

        try {
            if ($product && $product->stock >= $quantity) {
                //在庫がある場合
                $product->stock -= $quantity;
                $product->save();
                //売上の記録
                $sale = new Sale();
                $sale->product_id = $productId;
                // $sale->quantity = $quantity;
                $sale->save();

                DB::commit();

               return response()->json(['message' => config('messages.purchase')], 200);
           } else {
                //在庫が不足している場合
                DB::rollBack();
                return response()->json(['message' => config('messages.purchase_0')], 400);
            }
        } catch (\Exception $e) {
            //例外が発生した場合
           DB::rollBack();

            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}