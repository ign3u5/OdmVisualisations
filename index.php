<?php
	require_once "controller.php";
	$controller = new DataController();
?>

<html>
	<head>
		
		<script src="//cdn.amcharts.com/lib/4/core.js"></script>
		<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
		<script src="//cdn.amcharts.com/lib/4/maps.js"></script>
		<script src="//cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://www.gstatic.com/charts/loader.js"></script>
		<style>
			.container {
				display: grid;
				grid-template:
					'visualizations buttons' / 3fr 1fr;
			}
			.visualization_container {
				grid-area: visualizations;
			}
			.button_container {
				padding-right: 2px;
				grid-area: buttons;
				text-align: right;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="visualization_container">
				<div class="visualization show_type_chart" id="show_type_chart"></div>
				<div class="visualization ratings_chart" style="display:inline-block" id="ratings_chart"></div>
				<div class="visualization tv_shows_per_year" id="dashboard">
					<div class="tv_shows_per_year" id="filter"></div>
					<div class="tv_shows_per_year" style="display:inline-block" id="tv_shows_per_year"></div>
					<div class="tv_shows_per_year" id="table"></div>
				</div>
				<div class="visualization movies_per_year" style="display:inline-block" id="movies_per_year"></div>
				<div class="visualization average_duration" style="display:inline-block" id="average_duration"></div>
				<div class="visualization available_seasons" id="available_seasons"></div> 
				<div class="visualization top_actors" style="display:inline-block" id="top_actors"></div>
				<div class="visualization top_countries" style="display:inline-block" id="top_countries"></div>
				<div class="visualization map" style="display:inline-block; width:100%; height:100vh" id="map"></div>
			</div>
			<div class="button_container">
				<button id="btn_show_type">Show Type Comparison</button>
				<br>
				<button id="btn_ratings_chart">Show Ratings</button>
				<br>
				<button id="btn_tv_shows_per_year">Show TV shows per year</button>
				<br>
				<button id="btn_movies_per_year">Movies per Year</button>
				<br>
				<button id="btn_average_duration">Average Duration</button>
				<br>
				<button id="btn_available_seasons">Amount of Seasons</button>
				<br>
				<button id="btn_top_actors">Top Actors</button>
				<br>
				<button id="btn_top_countries">Top Countries</button>
				<br>
				<button id="btn_map">Map</button>
			</div>
		</div>
		<script src="charts.js"></script>
		<script>
			google.charts.load('current', {'packages':['corechart', 'controls']});
			
			//Callback to run when API has loaded
			google.charts.setOnLoadCallback(drawCharts);

			//var mapData = [];
			var countryInfoTest = [{"Name":"United Kingdom", "Total":123}, {"Name":"France", "Total":123}];
			var countryInfo = <?php echo json_encode($controller->GetTopCountries(10)) ?> ;
			console.log(`Country Info: ${JSON.stringify(countryInfo)}`);
			
			amChartsMap();
			
			function drawCharts() {
				showTypeComparison(<?php echo $controller->GetTotalShowType()['TV Show'] ?>, <?php echo $controller->GetTotalShowType()['Movie'] ?>);
				ratingsComparison(<?php echo json_encode($controller->GetTotalRatings())?>);
				tvShowsPerYear(<?php echo json_encode($controller->GetTotalTvShowsForReleaseYears())?>);
				moviesPerYear(<?php echo json_encode($controller->GetTotalMoviesForReleaseYears())?>);
				averageDuration(<?php echo $controller->GetAverageDuration()['TV Show'] ?>, <?php echo $controller->GetAverageDuration()['Movie'] ?>);
				availableSeasons(<?php echo json_encode($controller->GetAvailableSeasons())?>);
				topActors(<?php echo json_encode($controller->GetTopActors(10))?>);
				topCountries(<?php echo json_encode($controller->GetTopCountries(10))?>);
			}
			function showVisualizations(className) {
				console.log(`Running show visualizations for ${className}`);
				$(`.visualization`).hide();
				$(`.${className}`).show();
			}
			$(function(){
				$(`.visualization`).hide();
				$('#btn_show_type').on('click', function () {
					showVisualizations('show_type_chart') });
				$('#btn_tv_shows_per_year').on('click', function () { showVisualizations('tv_shows_per_year') });
				$('#btn_ratings_chart').on('click', function () { showVisualizations('ratings_chart') });
				$('#btn_movies_per_year').on('click', function () { showVisualizations('movies_per_year') });
				$('#btn_average_duration').on('click', function () { showVisualizations('average_duration') });
				$('#btn_available_seasons').on('click', function () { showVisualizations('available_seasons') });
				$('#btn_top_actors').on('click', function () { showVisualizations('top_actors') });
				$('#btn_top_countries').on('click', function () { showVisualizations('top_countries') });
				$('#btn_map').on('click', function () { showVisualizations('map') });
			});
		</script>
	</body>
</html>