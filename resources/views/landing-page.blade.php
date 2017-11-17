@extends('layouts.app1')

@section('title') Concepcion Catholic School @endsection

@section('content')

    @include('layouts.nav1')
    <div style="margin-top: 80px;"></div>
      <div class="slider" style="margin: 0 auto;">
        <div><img src="{{ asset('uploads/logo/bg2.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/1.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/2.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/3.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/5.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/6.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/7.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/8.jpg') }}" class="img-responsive img-thumbnail"></div>
        <!-- <div><img src="{{ asset('uploads/logo/imgs/img1.jpg') }}" class="img-responsive"></div> -->
        <div><img src="{{ asset('uploads/logo/imgs/img2.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/img3.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/img4.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/img5.jpg') }}" class="img-responsive img-thumbnail"></div>
        <div><img src="{{ asset('uploads/logo/imgs/img6.jpg') }}" class="img-responsive img-thumbnail"></div>
      </div>



    {{--<header>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="8"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="9"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="10"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="11"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="12"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="13"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="14"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="15"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="16"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="17"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <!-- Slide One - Set the background image for this slide in the line below -->
          <div class="carousel-item active" style="background-image: url({{  asset('uploads/logo/bg1.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>First Slide</h3>
              <p>This is a description for the first slide.</p> -->
            </div>
          </div>
          <!-- Slide Two - Set the background image for this slide in the line below http://placehold.it/1900x1080-->
          <!-- <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/bg.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
               <h3>Second Slide</h3>
              <p>This is a description for the second slide.</p> -->
            <!-- </div>
          </div> -->
          <!-- Slide Three - Set the background image for this slide in the line below -->
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/bg2.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/1.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/2.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/3.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/4.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/5.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/6.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/7.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/8.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/9.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
        
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img1.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img2.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img3.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img4.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img5.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img6.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
          <div class="carousel-item" style="background-image: url({{  asset('uploads/logo/imgs/img7.jpg') }})">
            <div class="carousel-caption d-none d-md-block">
              <!-- <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p> -->
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </header>--}}
@include('layouts.footer1')

@endsection
