<?php
/**
 * This is an pie/doughnut chart HTML template.
 *
 * $chart_title
 * $chart_id
 * $chart_span_id
 * $chart_labels
 * $chart_data
 * $chart_background_colors
 *
 *
 *
 * @var string
 */
// echo "<pre>";var_dump($chart_title);echo "</pre>";//exit();
?>
<div>
  <p style="text-align:center;margin-top: 0;font-size: 1.3em;margin-bottom: 13px;"><?php echo $chart_title; ?><br></p>
</div>

<div style="display: flex;align-items: center;justify-content: space-around;">
  <div style="margin: 0 auto;flex: 0 0 220px;max-width:220px;">
    <canvas id="<?php echo $chart_id; ?>"></canvas>
  </div>
  <div style="margin: 0 auto;">
    <p style="text-align: left;margin-bottom: 13px;"><span id="<?php echo $chart_span_id; ?>" class="<?php echo $chart_span_id; ?>"></span></p>
  </div>
</div>

<script>
    var <?php echo $chart_id; ?>_graph_element = document.getElementById("<?php echo $chart_id; ?>");
    // And for a doughnut chart
    var <?php echo $chart_id; ?>_data = {
        labels: [<?php echo implode(',', $chart_labels); ?>],
        datasets: [{
            data: [<?php echo $chart_data;?>],
            backgroundColor: [<?php echo implode(',', $chart_background_colors); ?>],
            borderWidth: 0,
        }]
    };
    var <?php echo $chart_id; ?>Chart = new Chart(<?php echo $chart_id; ?>_graph_element, {
        type: 'doughnut',
        data: <?php echo $chart_id; ?>_data,
        options: {
            maintainAspectRatio: true,
            cutoutPercentage: 90,
            responsive: true,
            tooltips: {
                enabled: true
            },
            labels: {
                boxWidth: 20,
                fontSize: 12,
            },
            legend: {
              display: false,
              position: 'bottom',
              labels: {
                  fontColor: 'rgb(255, 99, 132)'
              }
            },
            legendCallback: function(chart) {
              console.log(chart.data);
              var text = [];
              // text.push('<ul>');
              for (var i=0; i<chart.data.datasets[0].data.length; i++) {
                  // text.push('<li>');
                  // text.push('<span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '">' + chart.data.datasets[0].data[i] + '</span>');
                  text.push('<div class="pie-graph-sublabel"><span style="color: ' + chart.data.datasets[0].backgroundColor[i] + ';font-size: 1.6em;font-weight:500;">' + chart.data.datasets[0].data[i] + '</span> ');
                  if (chart.data.labels[i]) {
                      text.push(chart.data.labels[i]);
                  }
                  var exclude_last = chart.data.datasets[0].data.length - 1;
                  if(i != exclude_last) {
                    text.push('<br>');
                  }
                  text.push('</div>');
                  // text.push('</li>');
              }
              // text.push('</ul>');
              // text.push('</ul>');
              return text.join("");
          }
        }
    });

    Chart.pluginService.register({
      beforeDraw: function(chart) {
        var width = chart.chart.width,
            height = chart.chart.height,
            ctx = chart.chart.ctx,
            type = chart.config.type;

        if (type == 'doughnut')
        {
        	var percent = Math.round((chart.config.data.datasets[0].data[0] * 100) /
                        (chart.config.data.datasets[0].data[0] +
                        chart.config.data.datasets[0].data[1]));
    			var oldFill = ctx.fillStyle;
          var fontSize = ((height - chart.chartArea.top) / 100).toFixed(2);
          fontSize = (fontSize * 1.5).toFixed(2);

          ctx.restore();
          ctx.font = fontSize + "em sans-serif";
          ctx.textBaseline = "middle"

          var text = percent + "%",
              textX = Math.round((width - ctx.measureText(text).width) / 2),
              textY = (height + chart.chartArea.top) / 2;

          ctx.fillStyle = chart.config.data.datasets[0].backgroundColor[0];
          ctx.fillText(text, textX, textY);
          ctx.fillStyle = oldFill;
          ctx.save();
        }
      }
    });

    // Add custom legend to a specific DOM element
    jQuery("#<?php echo $chart_span_id; ?>").html(<?php echo $chart_id; ?>Chart.generateLegend());
</script>
