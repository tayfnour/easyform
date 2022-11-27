<div class="el">
    <div class="wrap-breadcrumb el">
        <ul class="el">
            <li class="item-link el"><a href="/" class="link el">home</a></li>
            <li class="item-link el"><span class="el">Cart</span></li>
        </ul>
    </div>
    <div class="row el">

        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 main-content-area el">



            <div class="banner-shop el">
                <a href="#" class="banner-link el">
                    <figure class="el"><img src="assets/images/shop-banner.jpg" alt="" class="el"></figure>
                </a>
            </div>

            <div class="wrap-shop-control el">



                <h1 class="shop-title el">Digital &amp; Electronics</h1>

                <div class="wrap-right el">

                    <div class="sort-item orderby  el">
                        <select name="orderby" class="use-chosen el">
                            <option value="menu_order" selected="selected" class="el">Default sorting</option>
                            <option value="popularity" class="el">Sort by popularity</option>
                            <option value="rating" class="el">Sort by average rating</option>
                            <option value="date" class="el">Sort by newness</option>
                            <option value="price" class="el">Sort by price: low to high</option>
                            <option value="price-desc" class="el">Sort by price: high to low</option>
                        </select>
                    </div>

                    <div class="sort-item product-per-page el">
                        <select name="post-per-page" class="use-chosen el">
                            <option value="12" selected="selected" class="el">12 per page</option>
                            <option value="16" class="el">16 per page</option>
                            <option value="18" class="el">18 per page</option>
                            <option value="21" class="el">21 per page</option>
                            <option value="24" class="el">24 per page</option>
                            <option value="30" class="el">30 per page</option>
                            <option value="32" class="el">32 per page</option>
                        </select>
                    </div>

                    <div class="change-display-mode el">
                        <a href="#" class="grid-mode display-mode active el"><i class="fa fa-th el"></i>Grid</a>
                        <a href="list.html" class="list-mode display-mode el"><i class="fa fa-th-list el"></i>List</a>
                    </div>

                </div>

            </div>
            end wrap shop control

            <div class="row el">

                <div class="el">
                     @if (session()->has('message')) 
                    <div class="alert alert-success el">
                        {{ session('message') }}
                    </div>
                    @endif
                    @php session()-&gt;forget('message');
                    @endphp
                </div>

                <div class="el">
                    @foreach(Cart::content() as $row) You have : {{ $row-&gt;qty }}
                    @endforeach
                </div>




                <ul class="product-list grid-products equal-container el">

                    @foreach ($products as $pro )

                    <li class="col-lg-4 col-md-6 col-sm-6 col-xs-6  el">
                        <div class="product product-style-3 equal-elem  el">
                            <div class="product-thumnail el">
                                <a href="/product/{{$pro->sku}}" title="{{$pro->pro_name}}" class="el">
                                    <figure class="el"><img src="http://{{ $uurl }}/easyPanel/storage/app/public/{{$pro->image}}" style="height: 200px" alt="T-Shirt Raw Hem Organic Boro Constrast Denim" class="el"></figure>
                                </a>
                            </div>
                            <div class="product-info el">
                                <a href="/product/{{$pro->name}}" class="product-name el"><span class="el">{{$pro-&gt;pro_name}} - [White]</span></a>
                                <div class="wrap-price el"><span class="product-price el">{{$pro-&gt;price}}</span></div>
                                <a href="#" class="btn add-to-cart el" wire:click.prevent="store({{$pro->id}} ,'{{$pro->pro_name}}',{{$pro->price}})">أضف إلى العربة</a>
                            </div>
                        </div>
                    </li>

                    @endforeach
                </ul>

            </div>

            <div class="wrap-pagination-info el">

                {{$products-&gt;links()}} {{--
                <ul class="page-numbers el">
                    <li class="el"><span class="page-number-item current el">1</span></li>
                    <li class="el"><a class="page-number-item el" href="#">2</a></li>
                    <li class="el"><a class="page-number-item el" href="#">3</a></li>
                    <li class="el"><a class="page-number-item next-link el" href="#">Next</a></li>
                </ul>
                <p class="result-count el">Showing 1-8 of 12 result</p> --}}
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 sitebar el">
            <div class="widget mercado-widget categories-widget el">
                <h2 class="widget-title el">All Categories</h2>
                <div class="widget-content el">
                    <ul class="list-category el">
                        @foreach ($cats as $cat)
                        {{-- secondry Level --}}
                        <li class="category-item has-child-cate el">
                            <a href="#" class="cate-link el">{{$cat-&gt;name}}</a>
                            <span class="toggle-control el">+</span>

                            <ul class="sub-cate el">

                                @foreach ($cats1 as $cat1 )
                                 @if($cat1->parent_id == $cat->code ) 
                                <li class="category-item  sec_level el"><a href="#" class="cate-link el"><span class="sec-level-title el">{{$cat1-&gt;name}}<span class="el"></span></span></a>
                                    <ul style="padding-right:20px;margin-bottom:20px" class="el">
                                        @foreach ($cats1 as $cat2 )
                                         @if($cat2->parent_id == $cat1->code ) 
                                        <li class="category-item el"><a href="#" class="cate-link el">{{$cat2-&gt;name}}</a></li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </li>
                                @endif
                                @endforeach
                            </ul>



                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
             Categories widget

            <div class="widget mercado-widget filter-widget brand-widget el">
                <h2 class="widget-title el">Brand</h2>
                <div class="widget-content el">
                    <ul class="list-style vertical-list list-limited el" data-show="6">
                        <li class="list-item el"><a class="filter-link active el" href="#">Fashion Clothings</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Laptop Batteries</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Printer &amp; Ink</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">CPUs &amp; Prosecsors</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Sound &amp; Speaker</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Shop Smartphone &amp; Tablets</a></li>
                        <li class="list-item default-hiden el"><a class="filter-link  el" href="#">Printer &amp; Ink</a></li>
                        <li class="list-item default-hiden el"><a class="filter-link  el" href="#">CPUs &amp; Prosecsors</a></li>
                        <li class="list-item default-hiden el"><a class="filter-link  el" href="#">Sound &amp; Speaker</a></li>
                        <li class="list-item default-hiden el"><a class="filter-link  el" href="#">Shop Smartphone &amp; Tablets</a></li>
                        <li class="list-item el"><a data-label="Show less<i class=&quot;fa fa-angle-up&quot; aria-hidden=&quot;true&quot;></i>" class="btn-control control-show-more el" href="#">Show more<i class="fa fa-angle-down el" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
            </div>
             brand widget

            <div class="widget mercado-widget filter-widget price-filter el">
                <h2 class="widget-title el">Price</h2>
                <div class="widget-content el">
                    <div id="slider-range" class="el"></div>
                    <p class="el">
                        <label for="amount" class="el">Price:</label>
                        <input type="text" id="amount" readonly="" class="el">
                        <button class="filter-submit el">Filter</button>
                    </p>
                </div>
            </div>
             Price

            <div class="widget mercado-widget filter-widget el">
                <h2 class="widget-title el">Color</h2>
                <div class="widget-content el">
                    <ul class="list-style vertical-list has-count-index el">
                        <li class="list-item el"><a class="filter-link  el" href="#">Red <span class="el">(217)</span></a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Yellow <span class="el">(179)</span></a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Black <span class="el">(79)</span></a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Blue <span class="el">(283)</span></a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Grey <span class="el">(116)</span></a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">Pink <span class="el">(29)</span></a></li>
                    </ul>
                </div>
            </div>
             Color 

            <div class="widget mercado-widget filter-widget el">
                <h2 class="widget-title el">Size</h2>
                <div class="widget-content el">
                    <ul class="list-style inline-round  el">
                        <li class="list-item el"><a class="filter-link active el" href="#">s</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">M</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">l</a></li>
                        <li class="list-item el"><a class="filter-link  el" href="#">xl</a></li>
                    </ul>
                    <div class="widget-banner el">
                        <figure class="el"><img src="assets/images/size-banner-widget.jpg" width="270" height="331" alt="" class="el"></figure>
                    </div>
                </div>
            </div>
             Size 

            <div class="widget mercado-widget widget-product el">
                <h2 class="widget-title el">Popular Products</h2>
                <div class="widget-content el">
                    <ul class="products el">
                        <li class="product-item el">
                            <div class="product product-widget-style el">
                                <div class="thumbnnail el">
                                    <a href="detail.html" title="Radiant-360 R6 Wireless Omnidirectional Speaker [White]" class="el">
                                        <figure class="el"><img src="assets/images/products/digital_01.jpg" alt="" class="el"></figure>
                                    </a>
                                </div>
                                <div class="product-info el">
                                    <a href="#" class="product-name el"><span class="el">Radiant-360 R6 Wireless Omnidirectional Speaker...</span></a>
                                    <div class="wrap-price el"><span class="product-price el">$168.00</span></div>
                                </div>
                            </div>
                        </li>

                        <li class="product-item el">
                            <div class="product product-widget-style el">
                                <div class="thumbnnail el">
                                    <a href="detail.html" title="Radiant-360 R6 Wireless Omnidirectional Speaker [White]" class="el">
                                        <figure class="el"><img src="assets/images/products/digital_17.jpg" alt="" class="el"></figure>
                                    </a>
                                </div>
                                <div class="product-info el">
                                    <a href="#" class="product-name el"><span class="el">Radiant-360 R6 Wireless Omnidirectional Speaker...</span></a>
                                    <div class="wrap-price el"><span class="product-price el">$168.00</span></div>
                                </div>
                            </div>
                        </li>

                        <li class="product-item el">
                            <div class="product product-widget-style el">
                                <div class="thumbnnail el">
                                    <a href="detail.html" title="Radiant-360 R6 Wireless Omnidirectional Speaker [White]" class="el">
                                        <figure class="el"><img src="assets/images/products/digital_18.jpg" alt="" class="el"></figure>
                                    </a>
                                </div>
                                <div class="product-info el">
                                    <a href="#" class="product-name el"><span class="el">Radiant-360 R6 Wireless Omnidirectional Speaker...</span></a>
                                    <div class="wrap-price el"><span class="product-price el">$168.00</span></div>
                                </div>
                            </div>
                        </li>

                        <li class="product-item el">
                            <div class="product product-widget-style el">
                                <div class="thumbnnail el">
                                    <a href="detail.html" title="Radiant-360 R6 Wireless Omnidirectional Speaker [White]" class="el">
                                        <figure class="el"><img src="assets/images/products/digital_20.jpg" alt="" class="el"></figure>
                                    </a>
                                </div>
                                <div class="product-info el">
                                    <a href="#" class="product-name el"><span class="el">Radiant-360 R6 Wireless Omnidirectional Speaker...</span></a>
                                    <div class="wrap-price el"><span class="product-price el">$168.00</span></div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
             brand widget

        </div>

    </div>
</div>