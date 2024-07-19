  @include('dashboard.layout.header')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Reviews</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Reviews</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @foreach($providers as $provider)
            @if((count($provider['reviews'])!=0))
        <div class="row">
          <div class="col-md-12">
            <div class="timeline">
                <div class="time-label">
                    <span class="bg-blue">{{$provider->name}}</span>
                </div>
              <div>
                  @foreach($provider['reviews'] as $review )
                  <i class="fas fa-envelope bg-blue"></i>
                <div class="timeline-item">
                        <div class="timeline-header">
                      {{$review->user->name}}
                  </div>
                        <div class="timeline-body">
                      {{$review->stars}} Stars
                  </div>
                        <div class="timeline-body">
                    {{$review->content}}
                      <a class="btn btn-danger btn-sm" href="/admin/deleteReview/{{$review->id}}">delete review</a>
                  </div>
                </div>
                      &nbsp;
                  @endforeach

              </div>
              <!-- END timeline item -->
            </div>
          </div>
          <!-- /.col -->
        </div>
              @endif
        @endforeach
            @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('success')}}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{Session::get('error')}}
                </div>
            @endif
      </div>
      <!-- /.timeline -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('dashboard.layout.footer')
