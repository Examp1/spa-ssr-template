<?php
$ids = [];

if(isset($content['list']) && count($content['list'])){
    foreach ($content['list'] as $item){
        $ids[] = $item['product_id'];
    }
}


if(count($ids)){
    $products = \App\Models\Products::query()->active()->whereIn('id',$ids)->get();
}
?>

@if($products)
    <section class="recomendSection">
        <div class="container">
            <h2>{{$content['title']}}</h2>
            <p class="subtext">{{$content['subtitle']}}</p>
            <div class="relatedProds">
                @foreach($products as $product)
                    @if(isset($product->categories[0]->path))
                        <div class="item">
                            <a href="{{$product->categories[0]->path}}/{{$product->slug}}">
                                <div class="imgWrp">
                                    <img src="{{get_image_uri($product->image)}}" alt="">
                                </div>
                                <span class="name">{{$product->getTranslation(app()->getLocale())->name}}</span>
                            </a>
                        </div>
                    @endif    
                @endforeach
            </div>
        </div>
    </section>
@endif
