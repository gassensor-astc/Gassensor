<?php
/* @var $this yii\web\View */
/* @var $chartData array */
/* @var $chartDataLine array */


$this->registerJsVar('chartDataS', $chartData['s1']);
$this->registerJsVar('chartDataTicks', $chartData['ticks']);
$this->registerJsVar('chartDataLine', $chartDataLine);

?>
<div class="site-index">

<h1>Admin</h1>
<style>
.admin_li li {
	font-size: 16px;
}
</style>
<ul class="admin_li">
  <li><a href="https://my.adminvps.ru/login" target="_blank">Хостинг</a></li>
  <li><a href="https://185.239.51.139:1500/ispmgr#/dashboard" target="_blank">ISP Panel</a></li>
  <li><a href="https://185.239.51.139/mP4nX42FST7AWgLy/phpmyadmin/index.php?route=/&amp;route=%2F" target="_blank">PHPMyAdmin</a></li>
</ul>

  <h1>Заказы на сайте</h1>

  <div id="orders_chart">
  </div>

</div>


<script>
  setTimeout(() => {
    $(document).ready(function() {

      /*


      $.jqplot.config.enablePlugins = true;

      s1 = chartDataLine;

      plot1 = $.jqplot('orders_chart',[s1],{
          title: '',
          seriesDefaults:{
              pointLabels: { show: false }
          },
          axes: {
              // xaxis: {
              //     renderer: $.jqplot.DateAxisRenderer,
              //     tickOptions: {
              //         formatString: '%#m/%#d/%y'
              //     },
              //     numberTicks: 4
              // },
              xaxis: {
                  renderer: $.jqplot.CategoryAxisRenderer,
                  tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                  rendererOptions:{
                      tickRenderer: $.jqplot.CanvasAxisTickRenderer
                  },
                  tickOptions:{
                      formatString: '%d.%m.%Y',
                      fontSize: '8pt',
                      fontFamily: 'Tahoma',
                      angle: -40
                  },
              },
              // yaxis: {
              //     tickOptions: {
              //         formatString: '$%.2f'
              //     }
              // }
          },
          highlighter: {
              sizeAdjust: 10,
              tooltipLocation: 'n',
              tooltipAxes: 'y',
              tooltipFormatString: '<b><i><span style="color:red;">%.0f</span></i></b>',
              useAxesFormatters: false
          },
          cursor:{
              show: true,
              zoom: false,
              showTooltip: false,
              // tooltipLocation: 'sw',
              style: 'pointer'
          },
          // cursor: {
          //     show: true
          // }
      });
*/

      $.jqplot.config.enablePlugins = true;
      var line1 = chartDataLine;

      function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
        // display series_label, x-axis_tick, y-axis value
        // return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];

        dt = new Date(plot.data[seriesIndex][pointIndex][0]).toLocaleDateString();
        let num = plot.data[seriesIndex][pointIndex][1];
        num = num.toFixed(0);
        summ = plot.data[seriesIndex][pointIndex][1];

        return '<div style="background-color: #ccc; padding: 3px; z-index: 999; position: relative; opacity: 1;">Дата: ' + dt + '<br>сумма продаж за месяц: ' + summ + '</div>';
      }

      plot1 = $.jqplot('orders_chart', [line1], {
        title: '',
        seriesDefaults:{
          pointLabels: { show: false }
        },
        animate: !$.jqplot.use_excanvas,
        seriesColors:['#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050'],
        // seriesDefaults: {
        //     renderer: $.jqplot.BarRenderer,
        //     rendererOptions: {
        //         varyBarColor: true,
        //         shadow: false, // тень
        //     },
        //     pointLabels: { show: true }
        // },
        axes: {
          xaxis: {
            renderer: $.jqplot.CategoryAxisRenderer,
            rendererOptions:{
              tickRenderer: $.jqplot.CanvasAxisTickRenderer
            },
            tickOptions:{
              formatString: '%d.%m.%Y',
              fontSize: '8pt',
              fontFamily: 'Tahoma',
              angle: 30
            },
          },
          yaxis:{
            min: 0
          },
          // yaxis:{
          //     // pad: 0,
          //     label: 'ѕродано лицензий',
          //     labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
          //     min: 0,
          //     max: 173.65,
          //     numberTicks: 5,
          //     rendererOptions:{
          //         tickRenderer:$.jqplot.CanvasAxisTickRenderer},
          //     tickOptions:{
          //         fontSize: '6pt',
          //         fontFamily: 'Tahoma',
          //         formatString: '%.0f',
          //         angle:0
          //     },
          // }
        },
        cursor:{
          show: true,
          zoom: false,
          showTooltip: false,
          // tooltipLocation: 'sw',
          style: 'pointer'
        },
        grid: {
          drawGridLines: false,        // wether to draw lines across the grid or not.
          gridLineColor: '#F1F1F1',    // *Color of the grid lines.
          background: '#fffdf6',      // CSS color spec for background color of grid.
          borderColor: '#000000',     // CSS color spec for border around grid.
          borderWidth: 0.3,           // pixel width of border around grid.
          shadow: false,               // draw a shadow for grid.
          shadowAngle: 45,            // angle of the shadow.  Clockwise from x axis.
          shadowOffset: 1.5,          // offset from the line of the shadow.
          shadowWidth: 3,             // width of the stroke for the shadow.
          shadowDepth: 3             // Number of strokes to make when drawing shadow.
        },
        highlighter: {
          show: true,
          //showTooltip: true,
          //sizeAdjust: 7.5,
          //yvalues: 4,
          //formatString: '<div style="background-color: #ccc; padding: 3px">ƒата: %d<br>»зменение выдачи: %s/10</div>',
          tooltipContentEditor: tooltipContentEditor
        },
        series:[{
          lineWidth: 1,
          fill: true,
          color: '#92d050',
          showMarker: false, // квадраты на углах
          shadow: true, // тень
          markerOptions: {style:'circle'}
        }]
      });

      $('.jqplot-xaxis-tick').each(function(i, elem) {
        // If the index is odd (1, 3, 5...), hide the label
        if (i % 4 !== 0) {
          $(this).hide();
        }
      });


      /*
      $.jqplot.config.enablePlugins = true;

      var s1 = chartDataS;
      var ticks = chartDataTicks;

      function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
          // display series_label, x-axis_tick, y-axis value
          // return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];

          dt = new Date(plot.data[seriesIndex][pointIndex][0]).toLocaleDateString();
          let num = plot.data[seriesIndex][pointIndex][1];
          num = num.toFixed(0);
          summ = plot.data[seriesIndex][pointIndex][2];

          return '<div style="background-color: #ccc; padding: 3px">месяц: ' + dt + '<br>сумма продаж за месяц: ' + summ + '</div>';
      }

      $.jqplot('orders_chart', [s1], {
          // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
          animate: !$.jqplot.use_excanvas,
          seriesDefaults:{
              renderer:$.jqplot.BarRenderer,
              pointLabels: { show: true }
          },
          axes: {
              xaxis: {
                  renderer: $.jqplot.CategoryAxisRenderer,
                  tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                  tickInterval: 40,
                  ticks: ticks,
                  tickOptions:{
                      angle: 30
                  },
              }
          },
          cursor:{
              show: true,
              zoom: false,
              showTooltip: false, // координаты курсора в углу
              // tooltipLocation: 'sw',
              style: 'pointer'
          },
          highlighter: {
              show: true,
              //showTooltip: true,
              //sizeAdjust: 7.5,
              //yvalues: 4,
              //formatString: '<div style="background-color: #ccc; padding: 3px">ƒата: %d<br>»зменение выдачи: %s/10</div>',
              tooltipContentEditor: tooltipContentEditor
          },
      });
      $('#orders_chart').bind('jqplotDataClick',
          function (ev, seriesIndex, pointIndex, data) {
              //$('#chart_info').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
              console.log('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
          }
      );

      $('.jqplot-xaxis-tick').each(function(i, elem) {
          // If the index is odd (1, 3, 5...), hide the label
          if (i % 9 !== 0) {
              $(this).hide();
          }
      });
       */
    });
  }, 1000);
</script>




