<section class="center" style="background-color: lightblue;">
	<div class="profile_pic"></div>
	<h1 class="name"><?= $Data["full_name"] ?> <small class="rank"><?= $Data["rank"] ?></small></h1>
	<!-- (struggler, novice, competent, senior, veteran, sage, legend) based on average score number of tests given -->
	<blockquote><?= $Data["about_me"] ?></blockquote>
</section>

<section>
	<p>Member since <?= $Data["registered_on"] ?></p>
</section>

<section>
	<?php
		$rows = query("SELECT * FROM tests WHERE user_id = ? AND public = 1", $Data["id"]);
		if (empty($rows)) {
			echo "<div class=\"nothing\"><h2>Nothing to Show Here...</h2><h3>:(</h3></div>";
		} else {
			echo "<div id=\"tabs\">
		<ul>
			<li><a href=\"" . $_SERVER['REQUEST_URI'] . "#tabs-1\">Tests History</a></li>
			<li><a href=\"".  $_SERVER['REQUEST_URI'] . "#tabs-2\">Stats</a></li>
		</ul>
		<div id=\"tabs-1\">
			<table class=\"tests_list bordered\">
				<thead>
					<tr>
						<td>#</td>
						<td>Test</td>
						<td>Total</td>
						<td>Obtained</td>
						<td>Percentage</td>
						<td>Date</td>
					</tr>
				</thead>

				<tbody>";
			$percentage_sum = 0;
			$total_public = 0;
			
			foreach ($rows as $key => $value) {
				if ($value["public"] == 1) {
					$percentage = round( ($value["score"]/$value["total"]) * 100, 2 );
					$percentage_sum += $percentage;
					$total_public++;

					$keys = array_keys($Model);
					echo "<tr><td>" . ($key + 1) . "</td>
					<td><a href=\"" . SITE_PROTOCOL . "://" . SITE_DOMAIN . SITE_BASE_LINK . "profile/" . $Data["username"] . "/" . $value["counter"] . "/\">" . $Model[ $keys[$value["test_id"]] ]["title"]  . "</a></td>
					<td>" . $value["total"] . "</td>
					<td>" . $value["score"] . "</td>
					<td>" . $percentage . "%</td>
					<td>" . $value["date"] . "</td></tr>";
				}
			}
			$average_percentage = $percentage_sum/$total_public;

			echo "</tbody>
			</table>
		</div>
		<div id=\"tabs-2\">
			<div id=\"chart\">graph goes here...</div>
		</div>
	</div>

	<h4>Average Percentage: " . $average_percentage . "%</h4>
	<p><em>Excludes tests which are not made publicly available by the user.</em></p>";
		}
	?>

</section>

<style>
	.profile_pic {
		width: 250px;
		height: 250px;
		background-image: url("<?= $Data["profile_pic"] ?>");
		display: inline-block;
		border-radius: 100%;
		background-position: center;
	}
	.name {
		font-weight: 400;
	}
	.rank {
		font-weight: 200;
		text-transform: uppercase;
		font-size: 26px;
	}

	.ui-tabs .ui-tabs-nav li {
		display: inline-block;
		list-style: none;
		margin: 1px .2em 0 0;
		border-bottom-width: 0;
		padding: 0;
		white-space: nowrap;
	}
	.ui-tabs .ui-tabs-nav .ui-tabs-anchor {
		padding: 1em 1.5em;
		text-decoration: none;
		float: left;
	}
	li.ui-tabs-active {
		background-color: #9de0f9;
	}
	.nothing {
		text-align: center;
		color: #e0e0e0;
	}
	.nothing h3 {
		font-size: 48px;
	}
</style>

<script type="text/javascript">
	google.charts.setOnLoadCallback(drawColColors);

	var chart_data = [];
	document.querySelectorAll(".tests_list tbody tr").forEach(function (item, index) {
		var percentage = +item.querySelector("td:nth-child(5)").innerHTML.match(/-?\d+\.?\d*/);
		chart_data[index] = [
			new Date(item.querySelector("td:nth-child(6)").innerHTML),
			percentage,
			"<div style=\"padding: 5px;\"><strong>" + item.querySelector("td:nth-child(2) a").innerHTML + "</strong></br>Percentage Score: " + percentage + "%</div>"
		]
	});
	console.log(chart_data)

	function drawColColors() {
		var data = new google.visualization.DataTable();
		data.addColumn('date', 'Date');
		data.addColumn('number', 'Percentage Score');
		data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});

		data.addRows(chart_data);

		var options = {
			title: 'Percentage Graph',
			colors: ['#9575cd', '#33ac71'],
			tooltip: {isHtml: true},
			hAxis: {
				title: 'Date',
			},
			vAxis: {
				title: 'Percentage'
			}
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
		chart.draw(data, options);
	}
</script>