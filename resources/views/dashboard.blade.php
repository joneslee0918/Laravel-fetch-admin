@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">supervised_user_circle</i>
                        </div>
                        <p class="card-category">Activated Users</p>
                        <h3 class="card-title">
                            {{$data['activated_users']}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                        </div>
                    </div>
                </div>
                <div class="card card-stats">
                    <div class="card-header card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">supervised_user_circle</i>
                        </div>
                        <p class="card-category">Deactivated Users</p>
                        <h3 class="card-title">
                            {{$data['deactivated_users']}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                        </div>
                    </div>
                </div>
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">supervised_user_circle</i>
                        </div>
                        <p class="card-category">Active Ads</p>
                        <h3 class="card-title">
                            {{$data['active_ads']}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                        </div>
                    </div>
                </div>
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">supervised_user_circle</i>
                        </div>
                        <p class="card-category">Closed Ads</p>
                        <h3 class="card-title">
                            {{$data['closed_ads']}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12" id="category_ads_chart"></div>
            <div class="col-lg-4 col-md-12 col-sm-12" id="breed_ads_chart"></div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawChart);

var category_ads_json = `<?php echo json_encode($data['category_ads'])?>`;
var breed_ads_json = `<?php echo json_encode($data['breed_ads'])?>`;

function drawChart() {
    var breed_ads_chart_data = google.visualization.arrayToDataTable(JSON.parse(breed_ads_json));
    var category_ads_chart_data = google.visualization.arrayToDataTable(JSON.parse(category_ads_json));

    var options = {
        'width': 600,
        'height': 560
    };
    var category_ads_chart = new google.visualization.PieChart(document.getElementById('category_ads_chart'));
    var breed_ads_chart = new google.visualization.PieChart(document.getElementById('breed_ads_chart'));
    category_ads_chart.draw(category_ads_chart_data, options);
    breed_ads_chart.draw(breed_ads_chart_data, options);
}
</script>
@endpush