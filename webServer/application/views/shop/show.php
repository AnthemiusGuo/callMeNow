<!-- BEGIN HEADER -->
<div class="header">
  <div class="container">
    <a class="site-logo" href="shop-index.html"><img src="" alt="Metronic Shop UI" height="50px"></a>


    <!-- BEGIN CART -->
    <div class="top-cart-block">
      <div class="top-cart-info">
        <a href="javascript:void(0);" class="top-cart-info-count">3 items</a>
        <a href="javascript:void(0);" class="top-cart-info-value">$1260</a>
      </div>
      <span class="glyphicon glyphicon-shopping-cart"></span>
                    
      <div class="top-cart-content-wrapper">
        <div class="top-cart-content">
          <ul class="scroller" style="height: 250px;">
            <li>
              <a href="shop-item.html"><img src="../../assets/frontend/pages/img/cart-img.jpg" alt="Rolex Classic Watch" width="37" height="34"></a>
              <span class="cart-content-count">x 1</span>
              <strong><a href="shop-item.html">Rolex Classic Watch</a></strong>
              <em>$1230</em>
              <a href="javascript:void(0);" class="del-goods">&nbsp;</a>
            </li>
          </ul>
          <div class="text-right">
            <a href="shop-shopping-cart.html" class="btn btn-default">View Cart</a>
            <a href="shop-checkout.html" class="btn btn-primary">Checkout</a>
          </div>
        </div>
      </div>            
    </div>
    <!--END CART -->

    <!-- BEGIN NAVIGATION -->
    <div class="header-navigation">
      <ul>            
        <li><a href="<?=$this->urls['front']?>">商户首页</a></li>
        <li><a href="<?=$this->urls['list']?>">商品列表</a></li>
        <li><a href="<?=$this->urls['info']?>">商户介绍</a></li>
      </ul>
    </div>
    <!-- END NAVIGATION -->
  </div>
</div>
<!-- Header END -->
<div class="page-slider margin-bottom-35">
      <!-- LayerSlider start -->
      
      <!-- LayerSlider end -->
</div>
<div class="main">
      <div class="container">
        <div class="row margin-bottom-40">
          <div class="col-sm-12 col-lg-3 col-md-3 ">
            <div class="shopInfoCard">
              <h4>[<?=$this->shopInfo->field_list['provinceId']->gen_show_value()?>]<?=$this->shopInfo->field_list['name']->gen_show_html()?></h4>
              <p class="shopDesc"><?=$this->shopInfo->field_list['desc']->gen_show_html()?></p>
              <p>
              <strong>地址</strong>&nbsp;&nbsp;&nbsp;<span class="dd_wide"><?=$this->shopInfo->field_list['addresses']->gen_show_html()?></span><br/>
              <strong>电话</strong>&nbsp;&nbsp;&nbsp;<span><?=$this->shopInfo->field_list['phone']->gen_show_html()?></span><br/>
              <strong>QQ</strong>&nbsp;&nbsp;&nbsp;<span><?=$this->shopInfo->field_list['qq']->gen_show_html()?></span><br/>
              <strong>微信</strong>&nbsp;&nbsp;&nbsp;<span><?=$this->shopInfo->field_list['weixin']->gen_show_html()?></span><br/>
              <strong>旺旺</strong>&nbsp;&nbsp;&nbsp;<span><?=$this->shopInfo->field_list['wangwang']->gen_show_html()?></span>
              </p>
            </div>
          </div>
          <div class="col-sm-12 col-lg-9 col-md-9 ">
            <!-- BEGIN SALE PRODUCT & NEW ARRIVALS -->
            <div class="row">
              <!-- BEGIN SALE PRODUCT -->
              
                <h2>新品上市</h2>
                <div class="col-sm-12 col-lg-3 col-md-6 ">              
                  <div class="product-item">
                    <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <h3><a href="shop-item.html">浅花纹</a></h3>
                    <div class="pi-price">价格: 请咨询</div>
                    <a href="#" class="btn btn-default add2cart">查看</a>
                    <div class="sticker sticker-sale"></div>
                  </div>
                </div>
                <div class="col-sm-12 col-lg-3 col-md-6 ">              
                  <div class="product-item">
                    <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <h3><a href="shop-item.html">浅花纹</a></h3>
                    <div class="pi-price">价格: 请咨询</div>
                    <a href="#" class="btn btn-default add2cart">查看</a>
                    <div class="sticker sticker-sale"></div>
                  </div>
                </div>
                <div class="col-sm-12 col-lg-3 col-md-6 ">              
                  <div class="product-item">
                    <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <h3><a href="shop-item.html">浅花纹</a></h3>
                    <div class="pi-price">价格: 请咨询</div>
                    <a href="#" class="btn btn-default add2cart">查看</a>
                    <div class="sticker sticker-sale"></div>
                  </div>
                </div>
                <div class="col-sm-12 col-lg-3 col-md-6 ">              
                  <div class="product-item">
                    <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <h3><a href="shop-item.html">浅花纹</a></h3>
                    <div class="pi-price">价格: 请咨询</div>
                    <a href="#" class="btn btn-default add2cart">查看</a>
                    <div class="sticker sticker-sale"></div>
                  </div>
                </div>
              <!-- END SALE PRODUCT -->
            </div>
            <!-- END SALE PRODUCT & NEW ARRIVALS -->
          </div>
        </div>
        

        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40 ">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-4">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Kids</a></li>
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Accessories</a></li>
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Sports</a></li>
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Brands</a></li>
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Electronics</a></li>
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Home &amp; Garden</a></li>
              <li class="list-group-item clearfix"><a href="shop-product-list.html"><i class="fa fa-angle-right"></i> Custom Link</a></li>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-8">
            <div class="row">
                <div class="col-sm-12 col-lg-4 col-md-4 ">              
              <div class="product-item">
                <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                <h3><a href="shop-item.html">浅花纹</a></h3>
                <div class="pi-price">价格: 请咨询</div>
                <a href="#" class="btn btn-default add2cart">查看</a>
                <div class="sticker sticker-sale"></div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-4 col-md-4 ">              
              <div class="product-item">
                <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                <h3><a href="shop-item.html">浅花纹</a></h3>
                <div class="pi-price">价格: 请咨询</div>
                <a href="#" class="btn btn-default add2cart">查看</a>
                <div class="sticker sticker-sale"></div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-4 col-md-4 ">              
              <div class="product-item">
                <img src="../../assets/frontend/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                <h3><a href="shop-item.html">浅花纹</a></h3>
                <div class="pi-price">价格: 请咨询</div>
                <a href="#" class="btn btn-default add2cart">查看</a>
                <div class="sticker sticker-sale"></div>
              </div>
            </div>
            </div>
             
              
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>