const CHART_WIDTH = 800;
const CHART_HEIGHT = 600;

function availableSeasons(inputData) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Total');
	data.addColumn('number', 'Seasons');
	data.addRows(getDataFromArrayOfObjects(inputData));

	console.log(`Availabe seasons data: ${JSON.stringify(getDataFromArrayOfObjects(inputData))}`);

	var options = {'title':'Amount of TV show seasons available',
					'width': CHART_WIDTH,
					'height': CHART_HEIGHT,
					legend: 'none'};

	var chart = new google.visualization.ColumnChart(document.getElementById('available_seasons'));
	chart.draw(data, options);
}

function topCountries(inputData) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Country');
	data.addColumn('number', 'Total');
	data.addRows(getDataFromArrayOfObjects(inputData));

	var options = {'title':'Top 10 countries',
				  'width': CHART_WIDTH,
				  'height': CHART_HEIGHT};

	var chart = new google.visualization.PieChart(document.getElementById('top_countries'));
	chart.draw(data, options);
}

function topActors(inputData) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Actor');
	data.addColumn('number', 'Total');
	data.addRows(getDataFromArrayOfObjects(inputData));

	var options = {'title':'Top 10 actors',
				   'width':CHART_WIDTH,
				   'height':CHART_HEIGHT};

	var chart = new google.visualization.PieChart(document.getElementById('top_actors'));
	chart.draw(data, options);
}

function averageDuration(tvShows, movies) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Show Type');
	data.addColumn('number', 'Average Duration');
	data.addRows([
		['TV Shows', tvShows],
				 ['Movies', movies]
	]);

	var options = {'title':'Average duration of a movie',
				   'width':CHART_WIDTH,
				   'height':CHART_HEIGHT};

	var chart = new google.visualization.ColumnChart(document.getElementById('average_duration'));
	chart.draw(data, options);
}

function moviesPerYear(inputData) {
	var data = new google.visualization.DataTable();
	data.addColumn('number', 'Year');
	data.addColumn('number', 'Total Movies');
	data.addRows(getIndexedDataFromArrayOfObjects(inputData));

	var options = {'title':'Movies released each year',
					hAxis: {
						title: 'Year',
					},
					vAxis: {
						title: 'Number of movies',
					},
					trendlines: { 
						0: {
							type: 'exponential',
							color: 'green',
						} 
					},
				   	width:CHART_WIDTH,
					height:CHART_HEIGHT,
					legend: 'none',
				};

	var chart = new google.visualization.ScatterChart(document.getElementById('movies_per_year'));
	chart.draw(data, options);
}

function tvShowsPerYear(inputData) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Year');
	data.addColumn('number', 'Total TV Shows');
	data.addRows(getDataFromArrayOfObjects(inputData));
	
	var dashboard = new google.visualization.Dashboard(
		document.getElementById('dashboard'));
	
	var donutRangeSlider = new google.visualization.ControlWrapper({
		'controlType': 'NumberRangeFilter',
		'containerId': 'filter',
		'options': {
			'filterColumnLabel': 'Total TV Shows'
		}
	});

	var options = {'title':'TV Shows released each year',
				   'width':CHART_WIDTH,
				   'height':CHART_HEIGHT};
	
	var table = new google.visualization.ChartWrapper({
		'chartType': 'Table',
		'containerId': 'table',
		'options': options
	});
		
	
	var pieChart = new google.visualization.ChartWrapper({
		'chartType': 'PieChart',
		'containerId': 'tv_shows_per_year',
		'options': options
	});
	
	dashboard.bind(donutRangeSlider, [pieChart, table]);
	dashboard.draw(data);
}

function ratingsComparison(inputData) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Rating Type');
	data.addColumn('number', 'Total');
	data.addRows(getDataFromArrayOfObjects(inputData));

	var options = {'title':'Representation of the different ratings',
				   'width':CHART_WIDTH,
				   'height':CHART_HEIGHT};

	var chart = new google.visualization.PieChart(document.getElementById('ratings_chart'));
	chart.draw(data, options);
}

function getDataFromArrayOfObjects(data)
{
	var rows = new Array();
	for (var index in data)
	{
		var values = Object.values(data[index]);
		rows.push([values[0], parseInt(values[1])]);
	}
	return rows;
}

function getIndexedDataFromArrayOfObjects(data)
{
	var rows = new Array();
	for (var index in data)
	{
		var values = Object.values(data[index]);
		rows.push([parseInt(values[0]), parseInt(values[1])]);
	}
	return rows;
}

function showTypeComparison(tvShows, movies) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Show Type');
	data.addColumn('number', 'Total');
	data.addRows([
		['TV Shows', tvShows],
				 ['Movies', movies]
	]);

	var options = {'title':'Representation of movies to tv shows',
				   'width':CHART_WIDTH,
				   'height':CHART_HEIGHT};

	var chart = new google.visualization.PieChart(document.getElementById('show_type_chart'));
	chart.draw(data, options);
}

function amChartsMap(countryInfo)
{
	$.getJSON("countryCodes.json", function(mapData) {
		var map = am4core.create("map", am4maps.MapChart);
		map.geodata = am4geodata_worldLow;
		map.projection = new am4maps.projections.Miller();
		var polygonSeries = new am4maps.MapPolygonSeries();
		var seriesData = [];
		for (const country in countryInfo)
		{
			var seriesDatum = mapData
			.filter(data => data.Name == countryInfo[country].Name)
			.map(data => ( { id: data.Code, name: data.Name, value: countryInfo[country].Total, fill: am4core.color("#F05C5C")}))[0];	
			if (seriesDatum)
			{
				seriesData.push(seriesDatum);
			}
		}
		polygonSeries.data = seriesData;

		polygonSeries.useGeodata = true;
		map.series.push(polygonSeries);

		polygonSeries.data = seriesData;

		var tooltips = polygonSeries.mapPolygons.template;
		tooltips.tooltipText = "{name}: {value}";
		tooltips.propertyFields.fill = "fill";

		var hoverExample = tooltips.states.create("hover");
		hoverExample.properties.fill = am4core.color("#248EC6");
	});
}