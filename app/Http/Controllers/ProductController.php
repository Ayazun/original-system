<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
        public function redirectPath ()
    {
        return 'products/index';
    }

    public function index(Request $request)//一覧画面

    {   
        $keyword = $request->input('keyword');
        $company_id = $request->input('company-id');
        $companies = company::all();
        $model = new Product();
        $products = $model->getList($keyword,$company_id);


        return view('index', ['products' => $products, 'companies' => $companies]);  
    }
    
    public function create()
    {
       
        $companies_model = new Company();
        $companies = $companies_model->getList();


        return view ('create',['companies'=>$companies]);
       
    }

    public function store(ArticleRequest $request)//新規登録画面
    {   
       DB::beginTransaction(); 
       try{
        $model = new Product();
        $image = $request->file('img_path');
       
        if($image){
            $file_name = $image->getClientOriginalName();
            $image->storeAs('public/img',$file_name);
            $img_path ='storage/img/'.$file_name;
           
      }else{
        $img_path =null; 
      }
        DB::commit();
        $registerProduct = $model->InsertProducts($request,$img_path);
        return redirect()->route('products.create')->with('successMessage', '登録に成功しました。');
    } catch (Exception $e) {   
        DB::rollBack();
    }    

        $request->validate([
            'product_name'=>'required|max:20',
            'company-id'=>'required|integer',
            'price'=>'required|integer',
            'stock'=>'required|integer',
            'comment'=>'required|max:140',
        ]);

        
    }

    public function show(Request $request,$id) //詳細画面
    {
        $model = new Product();
        $products = $model->getCompaniesList($id);

        return view ('show',['products' => $products],compact('products'));
        

    }

    public function destroy(Request $request, Products $products,$id)
    {   
     DB::beginTransaction();   
     try{
        $model = new Product;
        $model -> destroyProduct($id);
        $product = Product::findOrFail($request->id);
        $product->delete();
        DB::commit();
    } catch (Exception $e) {   
        DB::rollBack();
        return redirect()->route('products.index');
    }
    }

    public function edit($id)//編集画面表示
    {   
        $companies = DB::table('companies')->get();
        $model = new Product();
        $products = $model->getCompaniesList($id);

        return view('edit',['companies'=> $companies, 'products' => $products]);
    }
    

    // public function registedit(Request $request, $id)//編集画面
    // {   
    //     $model = new Product();
    //     $image = $request->file('img_path');
    // try{
    //    if($image){
    //         $file_name = $image->getClientOriginalName();
    //         $image->storeAs('public/img',$file_name);
    //         $img_path ='storage/img/'.$file_name;
    //         $model->registedit($request, $img_path, $id);
    //     }else{
    //     $model->registeditNoImg($request, $id);
    //     $img_path =null; 
    //     }
    //     DB::commit();
    //     $registerProduct = $model->InsertProducts($request,$img_path);
    //     return redirect()->route('products.edit');
    // } catch (Exception $e) {  
    //     DB::rollBack();
    // }
    //     $request->validate([
    //         'product_name'=>'required|max:20',
    //         'company-id'=>'required|integer',
    //         'price'=>'required|integer',
    //         'stock'=>'required|integer',
    //         'comment'=>'required|max:140',
    //     ]);

       
    // }
    
    public function update(ArticleRequest $request, $id)
    {   
    
        $model = new Product();

        $array = [
            'product_name' => $request->input('product_name'),
            'company-id' => $request->input('company-id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
        ];
        
        $image  = $request->file("img_path");

        if(isset($image)){
            $file_name = $image->getClientOriginalName();
            $image->storeAs('public/img',$file_name);
            $img_path ='storage/img/'.$file_name;
            $array['img_path']=$img_path;
           
        }
        $updateProducts = $model->updateProducts($array,$id);
        
        return redirect()->route('products.edit',$id)->with('successMessage', '登録に成功しました。');
    }

    public function showRegistForm() {

        $companies = company::all();

        return view('regist',['companies' => $companies]);
    
    }
    
    public function search(Request $request)
    {Log::info('処理開始');
        // キーワードと検索対象のメーカーIDを取得
        $keyword = $request->input('keyword');
        $searchCompany = $request->input('company-id');
        Log::info($searchCompany);
        // Product モデルのインスタンスを作成
        $products = new Product();
        $companies = new Company();
    
        // インスタンスを介して検索メソッドを呼び出し
        $products = (new Product())->search($keyword, $searchCompany, $request);
        $companies = Company::all();
    
        return view('index', ['products' => $products , 'companies' => $companies ,'keyword' => $keyword, 'searchCompany' => $searchCompany])->render();
    
    }

    }





