@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
            @include('message')
            <div class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Gallery</h4>
                    </div>
                </div>
                <div id="lightgallery" class="row">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-01.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-01.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-02.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-02.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-03.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-03.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-04.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-04.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-01.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-01.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-02.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-02.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-03.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-03.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-04.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-04.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-01.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-01.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-02.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-02.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-03.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-03.jpg" alt="">
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 m-b-20">
                        <a href="{{asset('img')}}/blog/blog-04.jpg">
                            <img class="img-thumbnail" src="{{asset('img')}}/blog/blog-04.jpg" alt="">
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
        @include('layouts.footer')
        @include('layouts.footer_script')