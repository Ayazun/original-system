<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public function getList() {
        $products = DB::table('products')->join('companies','company_id','=','companies.id')
                                         ->select('products.*','companies.company_name')
                                         ->get();return $products;
    } 
    public function getCompaniesList($id) {
        $products = DB::table("products")       ->join('companies', 'company_id', '=', 'companies.id')
                                                ->select('products.*','companies.company_name')
                                                ->where('products.id', '=', $id) ->first();
        return $products;                                        
    } 

    public function destroyProduct($id) {
        $products = DB::table('products')
                                               ->where('products.id', '=', $id) ->delete();
    }

    public function updateProducts($request, $products)
    {

        ([
            'product_name' => $request->product_name
        ])->save();
    }
    
    public function registArticle($request,$img_path) {
        $products = DB::table('products')
                                               ->insert([  
                                                           'company_id' => $data->input('company-id'),
                                                           'product_name' => $data->input('name'),
                                                           'price'       => $data->input('kakaku'),
                                                           'stock'       => $data->input('stock'),
                                                           'comment'     => $data->input('shosai'),
                                                           'img_path'    => $img_path,
                                                         ]);               
        protected $fillable=[
        'name',
        'company-id',
        'kakaku',
        'stock',
        'shosai',
        'created_at',
        'updated_at',                                               
                                                       
                            ];                                                
    }

    public function newImage($array,$id) {
        $products = DB::table('products')      
                                                
                                                ->where ('id',$id)                                 
                                                ->update($array);

                                                       
    }
    
    public function InsertProducts($request,$img_path) {
        $products = DB::table('products') 
                                                ->insert([ 
                                                           'company_id' => $request->input('company-id'),
                                                           'product_name' => $request->input('name'),
                                                           'price'       => $request->input('kakaku'),
                                                           'stock'       => $request->input('stock'),
                                                           'comment'    => $request->input('shosai'),
                                                           'img_path'    => $img_path,
                                                        ]);
        protected $fillable=[
            'name',
            'company-id',
            'kakaku',
            'stock',
            'shosai',
            'created_at',
            'updated_at',
                            ];
    }
    
   




}




