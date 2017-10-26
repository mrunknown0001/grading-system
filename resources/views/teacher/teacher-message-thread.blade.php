@extends('layouts.app')

@section('title') Teacher Messages - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Messages with {{ ucwords($student->firstname . ' ' . $student->lastname) }}
       
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">
      <hr>
      <!-- Your Page Content Here -->
      <div class="row">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
        <div class="col-md-10">
          <!-- DIRECT CHAT PRIMARY -->
          <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <form action="{{ route('teacher_send_message') }}" method="post" autocomplete="off">
                <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." class="form-control" required="" autofocus="true">
                      <span class="input-group-btn">
                        <input type="hidden" name="student_id" value="{{ $student->id }}" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary btn-flat">Send</button>
                      </span>
                </div>
              </form>
              <!-- <h3 class="box-title"></h3> -->
            </div>

            <div class="box-body" style="">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">

                @foreach($messages as $message)
                @if($message->sender == 2)
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg right">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">Me</span>
                    <span class="direct-chat-timestamp pull-right">{{ date('F d, Y H:i:s a', strtotime($message->created_at)) }}</span>
                  </div>
                  <!-- /.direct-chat-info -->

                  <div class="direct-chat-text">
                    {{ strtolower($message->message) }}
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->
                @else
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{ ucwords($message->user->firstname . ' ' . $message->user->lastname) }}</span>
                    <span class="direct-chat-timestamp pull-right">{{ date('F d, Y H:i:s a', strtotime($message->created_at)) }}</span>
                  </div>
                  <!-- /.direct-chat-info -->

                  <div class="direct-chat-text">
                    {{ strtolower($message->message) }}
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->
                @endif
                @endforeach

                <p class="text-center"><i>Start of Conversation</i></p>
              </div>
              <!--/.direct-chat-messages-->

            </div>
            <!-- /.box-body -->
            <div class="box-footer" style="">

            </div>
            <!-- /.box-footer-->
          </div>
          <!--/.direct-chat -->
        </div>

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('includes.footer')
</div>
<!-- ./wrapper -->

</div>
@endsection