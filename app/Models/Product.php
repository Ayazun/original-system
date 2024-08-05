<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable=[
        'name',
        'company_id',
        'price',
        'stock',
        'comment',
        'created_at',
        'updated_at',                                               
                                                       
     ];          

     // Productモデルがsalesテーブルとリレーション関係を結ぶためのメソッド
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


    // Productモデルがcompaniesテーブルとリレーション関係を結ぶ為のメソッドです
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getList() {
        $products = DB::table('products')
                       ->join('companies','products.company-id','=', 'companies.id')
                       ->select('products.*','companies.company_name')
                       ->get();
        return $products;                            

    } 
    
    public function create() {
        $companies = DB::table('companies')->create();
        return view('product.create', ['companies' => $companies]);
    }

    public function getCompaniesList($id) {
        $products = DB::table('products')       
                        ->join('companies', 'products.company-id', '=', 'companies.id')
                        ->select('products.*','companies.company_name')
                        ->where('products.id', '=', $id) ->first();
        return $products;                                        
    }   

    //更新処理（画像あり）
    public function registedit($requst,$img_path, $id){
        $products = DB::table('products')      
                        ->where('products_id', '=', '$id')
                        ->update([
                                    'product_name' => $request->input('product_name'),
                                    'price'       => $request->input('price'),
                                    'stock'       => $request->input('stock'),
                                    'comment'     => $request->input('comment'),
                                    'img_path'    => $img_path,
                                 ]);               
    }
    
    //更新処理（画像なし）
    public function registeditnoimg($requst, $id){
        $products = DB::table('products')      
                        ->where('products_id', '=', '$id')
                        ->update([
                                    'product_name' => $request->input('product_name'),
                                    'price'       => $request->input('price'),
                                    'stock'       => $request->input('stock'),
                                    'comment'     => $request->input('comment'),
                                ]);               
    }

    
    public function updateProducts($array,$id) {
        $products = DB::table('products')
                        ->where('id',$id)
                        ->update($array);
           
    }


    public function destroyProduct($id) {
        $products = DB::table('products')
                        ->where('products.id', '=', $id) 
                        ->delete();
    }


   
    
    public function registArticle($request,$img_path) {
        $products = DB::table('products')
                        ->insert([  
                                    'company-id' => $data->input('company-id'),
                                    'product_name' => $data->input('product_name'),
                                    'price'       => $data->input('price'),
                                    'stock'       => $data->input('stock'),
                                    'comment'     => $data->input('comment'),
                                    'img_path'    => $img_path,
                                ]);               
                                             
    }
    
    public function InsertProducts($request,$img_path)  {
        $products = DB::table('products') 
                        ->insert([ 
                                    'company-id' => $request->input('company-id'),
                                    'product_name' => $request->input('product_name'),
                                    'price'       => $request->input('price'),
                                    'stock'       => $request->input('stock'),
                                    'comment'    => $request->input('comment'),
                                    'img_path'    => $img_path,
                                ]);
    
    }
    
    public function search($keyword, $searchCompany, $request)
{
    // products テーブルと companies テーブルを join
    $query = DB::table('products')
        ->join('companies', 'products.company-id', '=', 'companies.id')
        ->select('products.*', 'companies.company_name');
        

    if ($keyword) {
        $query->where('products.product_name', 'like', "%{$keyword}%");
    }

    if ($searchCompany) {
        $query->where('products.company-id', '=', $searchCompany);
    }
     // 最小価格が指定されている場合、その価格以上の商品をクエリに追加
    if($min_price = $request->min_price){
        $query->where('products.price', '>=', $min_price);
    }

    // 最大価格が指定されている場合、その価格以下の商品をクエリに追加
    if($max_price = $request->max_price){
        $query->where('products.price', '<=', $max_price);
    }

   // 最小在庫数が指定されている場合、その在庫数以上の商品をクエリに追加
    if($min_stock = $request->min_stock){
       $query->where('products.stock', '>=', $min_stock);
    }

    // 最大在庫数が指定されている場合、その在庫数以下の商品をクエリに追加
    if($max_stock = $request->max_stock){
       $query->where('products.stock', '<=', $max_stock);
    }
    return $query->get();
}



}
