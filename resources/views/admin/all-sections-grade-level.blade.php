@extends('layouts.app')

@section('title')  Sections on {{ $grade_level->name }} - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sections on {{ $grade_level->name }}
      </h1>

    </section>
    <section class="content">
      @foreach($sections as $sec)
      <h3><a href="#">{{ ucwords($sec->name) }}</a></h3>
      @endforeach

      @if($sections->count() == 0)
      <h2>No Sections Found!</h2>
      @endif
    </section>
  </div>

  @include('includes.footer')
</div>
@endsection