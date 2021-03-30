<?php
	require_once "controller.php";
	$controller = new DataController();
	echo "All show types: ".json_encode($controller->GetTotalShowType());
	echo "Number of TV Shows: ".$controller->GetTotalShowType()['TV Show'];
	echo nl2br("\n\nNumber of Movies: ".$controller->GetTotalShowType()['Movie']);
	echo nl2br("\n\nNumber of Shows: ".($controller->GetTotalShowType()['Movie'] + $controller->GetTotalShowType()['TV Show']));
	echo nl2br("\n\nNumber of shows rated PG: ".from($controller->GetTotalRatings())->where(function ($v) {return $v->Rating == 'PG';})->select()->Total);
	echo nl2br("\n\nRatings: ".json_encode($controller->GetTotalRatings()));
	echo nl2br("\n\nTV Shows for Release Years: ".json_encode($controller->GetTotalTvShowsForReleaseYears()));
	echo nl2br("\n\nMovies for Release Years: ".json_encode($controller->GetTotalMoviesForReleaseYears()));
	echo nl2br("\n\nAverage duration for show type: ".json_encode($controller->GetAverageDuration()));
	echo nl2br("\n\nTotal number of Seasons: ".$controller->GetTotalNumberOfSeasons());
	echo nl2br("\n\nList of Top 10 Actors: ".json_encode($controller->GetTopActors(10)));
	echo nl2br("\n\nList of Top 10 Countries: ".json_encode($controller->GetTopCountries(10)));
	echo nl2br("\n\nThe most popular country: ".$controller->GetTopCountries(1)[0]->Name);
	echo nl2br("\n\nAll country names: ".json_encode($controller->GetAllCountries()));
	echo nl2br("\n\nFirst country: ".$controller->GetAllCountries()[0]);
	echo nl2br("\n\nList of Afghan movies: ".json_encode($controller->GetAllCountryMovies("Afghanistan")));
	echo nl2br("\n\nGet available seasons: ".json_encode($controller->GetAvailableSeasons()));
?>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://www.gstatic.com/charts/loader.js"></script>
		<script src="charts.js"></script>
		<script>
			google.charts.load('current', {'packages':['corechart', 'controls']});
			
			//Callback to run when API has loaded
			google.charts.setOnLoadCallback(drawCharts);

			function drawCharts() {
				showTypeComparison(<?php echo $controller->GetTotalShowType()['TV Show'] ?>, <?php echo $controller->GetTotalShowType()['Movie'] ?>);
				ratingsComparison(<?php echo json_encode($controller->GetTotalRatings())?>);
				tvShowsPerYear(<?php echo json_encode($controller->GetTotalTvShowsForReleaseYears())?>);
				moviesPerYear(<?php echo json_encode($controller->GetTotalMoviesForReleaseYears())?>);
				averageDuration(<?php echo $controller->GetAverageDuration()['TV Show'] ?>, <?php echo $controller->GetAverageDuration()['Movie'] ?>);
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
				$('#btn_tv_shows_per_year').on('click', function () { showVisualizations('tv_shows_per_year') });
				$('#btn_ratings_chart').on('click', function () { showVisualizations('ratings_chart') });
			});
		</script>
		<style>
			.container {
				display: grid;
				grid-template:
					'visualizations buttons' / 50vw 50vw;
			}
			.visualization_container {
				grid-area: visualizations;
			}
			.button_container {
				grid-area: buttons;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="visualization_container">
				<div class="visualization" id="show_type_chart"></div>
				<div class="visualization ratings_chart" style="display:inline-block" id="ratings_chart"></div>
				<div class="visualization tv_shows_per_year" id="dashboard">
					<div class="tv_shows_per_year" id="filter"></div>
					<div class="tv_shows_per_year" style="display:inline-block" id="tv_shows_per_year"></div>
					<div class="tv_shows_per_year" id="table"></div>
				</div>
				<div class="visualization" style="display:inline-block" id="movies_per_year"></div>
				<div class="visualization" cstyle="display:inline-block" id="average_duration"></div>
				<div class="visualization" style="display:inline-block" id="top_actors"></div>
				<div class="visualization" style="display:inline-block" id="top_countries"></div>
			</div>
			<div class="button_container">
				<button id="btn_ratings_chart">Show Ratings</button>
				<br>
				<button id="btn_tv_shows_per_year">Show TV shows per year</button>
			</div>
		</div>
	</body>
</html>