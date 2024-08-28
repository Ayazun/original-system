@extends('list')
  
@section('content')
    <div class="row">
        <div class="col-lg-12">   
            <div class="text-left">
                <h2 style="font-size:1rem;">商品情報一覧画面</h2>
            </div>
            <div class="text-right">
            <a class="btn btn-success" href="{{ route('products.create') }}">新規登録</a>
            </div>
        </div>
    </div>
    <div>
    <form action="{{ route('products.search') }}" method="GET">
     @csrf    
    <dl class="search-box card-body mb-0">
                <dt>商品名</dt>
                <dd>
                <input type="text" name="keyword" class="form-control" placeholder="商品名" value="{{ $keyword ?? '' }}">
                </dd>
                <div class="left">
                    <div class="form-group">
                     <select class="form-select" id="company-id" name="company-id" placeholder="会社名を検索">
                       <option value="">メーカーを選択してください</option>
                    @foreach ($companies as $company)
                    <option value= "{{ $company->id }}" {{ $company->company_name }}>   
                    {{ $company->company_name }}
                    </option>
                    @endforeach
                    </select>
                    </div>
                </div>  
                <div id='form_price'>
                <input type="number" name="min_price" class="form-control" placeholder="最小価格" value="{{ request('min_price') }}">
                <input type="number" name="max_price" class="form-control" placeholder="最大価格" value="{{ request('max_price') }}">
                <input type="number" name="min_stock" class="form-control" placeholder="最小在庫" value="{{ request('min_stock') }}">
                <input type="number" name="max_stock" class="form-control" placeholder="最大在庫" value="{{ request('max_stock') }}">
    </div>
            <div class="card-footer">
                <button type="submit" class="btn w-100 btn-success">検索</button>
            </div>
        </form>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>product_name</th>
            <th>price</th>
            <th>stock</th>
            <th>company_name</th>
            <th>comment</th>
            <th>img_path</th>
        </tr>
        @foreach ($products as $product)
        <tr>
            <td style="text-align:right">{{ $product->id }}</td>
            <td>{{ $product->product_name }}</td>
            <td style="text-align:right">{{ $product->price }}円</td>
            <td style="text-align:right">{{ $product->stock }}本</td>
            <td style="text-align:right">{{ $product->company_name }}</td>
            <td style="text-align:right">{{ $product->comment }}</td>
            <td><img src="{{ asset($product->img_path) }}"width=100px></td>
            <td style="text-align:center">
            <form action="{{route('products.destroy',$product->id)}}"method="POST">
            @csrf
            @method('delete')
            <form  class="id">
                <input id="deleteTarget" data-user_id="{{$product->id}}" type="submit" class="btn btn-danger btn-sm mx-1" value="削除">
            </form>
            </form>
            </td>
            <td><a href="{{ route('products.show', ['id'=>$product->id])}}" class="btn btn-primary">詳細</a></td>
        </tr>
        @endforeach
    </table>
    
    <script>
            // ここからトークン送信処理
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //ここから非同期処理の記述
            $(function()) {
            //削除ボタンに"btn-danger"クラスを設定しているため、ボタンが押された場合に開始されます
                          $('.btn-danger').on('click', function(e)) {
                            e.preventDefault();
                            var deleteConfirm = confirm('削除してよろしいでしょうか？');    
            //　メッセージをOKした時（true)の場合、次に進みます 
                            if(deleteConfirm == true) {
                            var clickEle = $(this)
             //$(this)は自身（今回は押されたボタンのinputタグ)を参照します
             //　"clickEle"に対して、inputタグの設定が全て代入されます
  
                            var productsID = clickEle.attr('data-user_id');
                            }                
  
            $.ajax({
                    url: '/products/' + productsID,
                    type: 'POST',
                    data: {'id':productsID,'_method':'DELETE'}
                   })
            }};
        // 成功
        　　.done(function (results){
           

            // 通信成功時の処理
            console.log("results : " + results);        
            window.location.href = "/products.index";     //削除後に画面を遷移

        })
        // 失敗
        　　.fail(function(jqXHR, textStatus, errorThrown){
            //alert('失敗');
            console.log("ajax通信に失敗しました");
            console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
            console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
            console.log("errorThrown    : " + errorThrown.message); // 例外情報
            console.log("URL            : " + url);        
        });    
    </script>
@endsection
