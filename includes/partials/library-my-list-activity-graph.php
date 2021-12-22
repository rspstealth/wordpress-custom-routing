<?php
/**
 * This is an activity chart HTML template.
 * A line graph. It needs:
 *
 * $chart_data_id
 * $activity_chart_data
 * $no_activity
 *
 * @var string
 */

?>

<div style="" >
  <canvas id="<?php echo $chart_data_id; ?>" ></canvas>
</div>

<script>
    var activites = document.getElementById("<?php echo $chart_data_id; ?>");
    var data = {
        labels: [ <?php echo $activity_chart_data['line_graph_labels'];?> ],
        datasets: [
            <?php echo $activity_chart_data['completed_graph_data'];?>,
            <?php echo $activity_chart_data['in_progress_graph_data'];?>
        ]
    };

    var <?php echo 'chart_'.$chart_data_id; ?> = new Chart(activites, {
        type: 'line',
        data: data,
        height: 300,
        options: {
            legend: {
                display: false,
                labels: {}
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        suggestedMax: 5,
                        beginAtZero: true,
                        min: 0,
                        stepSize: <?php echo $activity_chart_data['step_height'];?>,
                        fixedStepSize: <?php echo $activity_chart_data['fix_step_size'];?>
                    },
                    gridLines: {
                        display:true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display:false
                    }
                }]
            },
            <?php if($no_activity) : ?>
            tooltips: {
              enabled: false
            },
            <?php endif; ?>
        }
    });

    // activites.height = 300;

    // Add the following extension only if there's no activity yet. Applies to the line chart
    <?php if($no_activity) : ?>
    Chart.pluginService.register({
      afterDraw: function(chart) {
        // We are working with the only line chart on the page.
        if(chart.config.type == 'line') {

          var width = chart.chart.width,
              height = chart.chart.height,
              ctx = chart.chart.ctx;

          // Draw a custom rectangle and fill it with semi transparent fill
          ctx.rect(0, 0, width, height);
          ctx.fillStyle = "rgba(255, 255, 255, 0.5)";
          ctx.fill();

          ctx.restore();
          var fontSize = (height / 114).toFixed(2);
          // ctx.font = fontSize + "em sans-serif";
          ctx.font = "1em sans-serif";
          ctx.fillStyle = "#505050";
          ctx.textBaseline = "middle";

          // This will approximate the height measurement: width of uppercase M might translate into line height
          var measureText1 = ctx.measureText('M').width;

          var text = "You do not have any activity yet.",
              textX = Math.round((width - ctx.measureText(text).width) / 2),
              textY = height / 2;

          // move it up a notch
          textY = textY - measureText1;

          var text2 = "Complete a lesson to see your progress";
          var text2X = Math.round((width - ctx.measureText(text2).width) / 2);
          var text2Y = textY + measureText1 + measureText1;

          ctx.fillText(text, textX, textY);
          ctx.fillText(text2, text2X, text2Y);
          ctx.save();
        }
      }
    });

    <?php endif; ?>

  // Display time and date got the user in their local browser's format
  d = new Date();
  jQuery('#locale_date').html(d.toLocaleString());
</script>
