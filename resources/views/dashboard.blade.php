@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
              <div class="card-icon">
                <i class="material-icons">person</i>
              </div>
              <p class="card-category">Admin</p>
              <h3 class="card-title">
                {{$dashboard['admin']}}
                <!-- <small>GB</small> -->
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <!-- <i class="material-icons text-danger">warning</i> -->
                <!-- <a href="#pablo">Get More Space...</a> -->
              </div>
            </div>
          </div>
          <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
              <div class="card-icon">
                <i class="material-icons">supervised_user_circle</i>
              </div>
              <p class="card-category">total User</p>
              <h3 class="card-title">
                {{$dashboard['otheruser']}}
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <!-- <i class="material-icons">date_range</i> Last 24 Hours -->
              </div>
            </div>
          </div>
          <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
              <div class="card-icon">
                <i class="material-icons">mail_outline</i>
              </div>
              <p class="card-category">Total News</p>
              <h3 class="card-title">
                {{$dashboard['news']}}
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <!-- <i class="material-icons">local_offer</i> Tracked from Github -->
              </div>
            </div>
          </div>
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="material-icons">group</i>
              </div>
              <p class="card-category">Total Team</p>
              <h3 class="card-title">
                {{$dashboard['group']}}
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <!-- <i class="material-icons">update</i> Just Updated -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12" id="piechart"></div>
        <div class="col-lg-4 col-md-12 col-sm-12" id="piechart1"></div>
        <div class="col-md-12 col-lg-12 col-sm-12 fresh-datatables" style="background:#fff; padding-top:30px">
          @if (session('status'))
            <div class="row">
              <div class="col-sm-12">
                <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                  </button>
                  <span>{{ session('status') }}</span>
                </div>
              </div>
            </div>
          @endif
          <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%"  style='text-align:center'>
            <thead class=" text-primary">
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Nickname</th>
                <th>Contact</th>
                <th>Date</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              @foreach($dashboard['contact'] as $index => $contact)
                <tr>
                  <td>{{$index+1}}</td>
                  <td>{{$contact->user['name']}} {{$contact->user['surname']}}</td>
                  <td>{{$contact->user['nickname']}}</td>
                  <td>{{$contact->contact}}</td>
                  <td>{{date('H:i d M Y', strtotime($contact->created_at))}}</td>
                  <td>
                    <form action="{{ route('home.destroy', $contact) }}" method="post" style="margin:0; padding:0">
                      @csrf
                      @method('delete')
                      <button rel="tooltip" type="button" class="btn btn-danger btn-link" data-original-title="Delete" title="Delete" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                          <i class="material-icons">close</i>
                          <div class="ripple-container"></div>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('js')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    var admin = `<?php echo $dashboard['admin']?>`;
    var partner = `<?php echo $dashboard['partner']?>`;
    var user = `<?php echo $dashboard['user']?>`;
    var superuser = `<?php echo $dashboard['superuser']?>`;
    var external = `<?php echo $dashboard['external']?>`;
    
    var clubNews = `<?php echo $dashboard['clubnews']?>`;
    var partnerNews = `<?php echo $dashboard['partnernews']?>`;
    var otherNews = `<?php echo $dashboard['othernews']?>`;
    var draft = `<?php echo $dashboard['draft']?>`;


    function drawChart() {
      var userData = google.visualization.arrayToDataTable([
        ['User', 'member'],
        ['Admin', parseInt(admin)],
        ['Partner', parseInt(partner)],
        ['User', parseInt(user)],
        ['Super User', parseInt(superuser)],
        ['External', parseInt(external)]
      ]);
      var newsData = google.visualization.arrayToDataTable([
        ['News', 'Count'],
        ['Club News', parseInt(clubNews)],
        ['Partner News', parseInt(partnerNews)],
        ['Other News', parseInt(otherNews)],
        ['User Draft', parseInt(draft)]
      ]);
      var options = {'width':600, 'height':560};
      var userChart = new google.visualization.PieChart(document.getElementById('piechart'));
      var newschart = new google.visualization.PieChart(document.getElementById('piechart1'));
      userChart.draw(userData, options);
      newschart.draw(newsData, options);
    }
  </script>
@endpush