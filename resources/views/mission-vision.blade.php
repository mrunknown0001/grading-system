@extends('layouts.app1')

@section('title') Concepcion Catholic School @endsection

@section('content')

    @include('layouts.nav1')
    <hr>
    <hr>
    <div style="margin-top: 50px; padding: 25px; text-align: justify;">
      <h3 class="text-center">Vision</h3>
      <h4>&nbsp;&nbsp;&nbsp;Concepcion Catholic School is a Filipino Catholic institution dedicated to quiality education, animated by the Spirit of the Gospel and inspired by the examples of the Blessed Virgin Mary and St. Dominic.</h4>
      <hr>
      <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
          
          <h3 class="text-center">Mission</h3>
          <ul class="disk">
            <li><h4>To develop Christian and moral values</h4></li>
            <li><h4>To provide quality education to our pupils and students</h4></li>
            <li><h4>To integrate the aspects of their human knowledge to their daily experiences</h4></li>
          </ul>
          
        </div>
      </div>
    </div>
@endsection
