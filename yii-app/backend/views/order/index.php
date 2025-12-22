<?php
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $chartData array */
/* @var $chartDataLine array */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsVar('chartDataS', $chartData['s1']);
$this->registerJsVar('chartDataTicks', $chartData['ticks']);
$this->registerJsVar('chartDataLine', $chartDataLine);

?>
<div class="row-fluid">

    <div class="col">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">

            <!-- widget div-->
            <div>

                <h1><?= Html::encode($this->title) ?></h1>

                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12">

                            <?= Html::a('<i class="far fa-file-excel"></i> Экспорт в Excel', Url::current(['export-excel']), ['class' => 'btn btn-sm btn-primary ml-5']) ?>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-5 mb-1">
                            <div id="chart_info">
                            </div>
                            <div id="orders_chart">
                            </div>
                        </div>
                    </div>

                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::beginForm(['batch'], 'post', ['class' => 'batch d-none']) ?>
                                Выделенные
                                <?= Html::hiddenInput('data') ?>
                                <?= Html::submitButton('<i class="fa fa-trash-alt"></i> Удалить', [
                                    'name' => 'action',
                                    'value' => 'delete',
                                    'class' => 'btn btn-danger']) ?>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    </div>

                    <!--
    <p>
        <?= Html::a(Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
 -->

                    <br>

                    <div class="table-responsive">
                        <?php Pjax::begin(); ?>
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\CheckboxColumn'],
                                ['class' => 'yii\grid\SerialColumn'],
                                'id',
                                'created_at:dateTime',
                                [
                                    'attribute' => 'products',
                                    'label' => 'Товары',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-products', ['model' => $model,]);
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-status', ['model' => $model,]);
                                    }
                                ],
                                'name',
                                'email:email',
                                'phone',
                                'delivery_info:ntext',
                                'comment:ntext',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>
                    </div>

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->

    </div>

</div>

<script>
    setTimeout(() => {
        $(document).ready(function() {

            $.jqplot.config.enablePlugins = true;
            var line1 = chartDataLine;

            function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
                // display series_label, x-axis_tick, y-axis value
                // return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];

                dt = new Date(plot.data[seriesIndex][pointIndex][0]).toLocaleDateString();
                let num = plot.data[seriesIndex][pointIndex][1];
                num = num.toFixed(0);
                summ = plot.data[seriesIndex][pointIndex][1];

                return '<div style="background-color: #ccc; padding: 3px">дата: ' + dt + '<br>сумма продаж за день: ' + summ + ' руб.</div>';
            }

            plot1 = $.jqplot('orders_chart', [line1], {
                //title: 'ѕродажи по мес¤цам (28 мес., 1606 продаж): MIN 22, MAX 151 &mdash; &Delta; 57',
                animate: !$.jqplot.use_excanvas,
                seriesColors:['#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050','#92d050'],
                seriesDefaults: {
                    renderer: $.jqplot.BarRenderer,
                    rendererOptions: {
                        varyBarColor: true,
                        shadow: false, // тень
                    },
                    pointLabels: { show: true }
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        rendererOptions:{
                            tickRenderer: $.jqplot.CanvasAxisTickRenderer},
                        tickOptions:{
                            formatString: '%d.%m.%Y',
                            fontSize: '8pt',
                            fontFamily: 'Tahoma',
                            angle: -40
                        },
                    },
                    yaxis:{
                        // pad: 0,
                        //label: 'ѕродано лицензий',
                        labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                        min: 0,
                        //max: 173.65,
                        numberTicks: 5,
                        rendererOptions:{
                            tickRenderer:$.jqplot.CanvasAxisTickRenderer},
                        tickOptions:{
                            fontSize: '6pt',
                            fontFamily: 'Tahoma',
                            formatString: '%.0f',
                            angle:0
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
                    showMarker: false, // квадраты на углах
                    shadow: true, // тень
                    markerOptions: {style:'circle'}
                }]
            });
            return;

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

                return '<div style="background-color: #ccc; padding: 3px; z-index: 999; position: relative; opacity: 1;">Дата: ' + dt + '<br>сумма продаж за день: ' + summ + '</div>';
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
                    showMarker: false, // квадраты на углах
                    shadow: true, // тень
                    fill: true,
                    color: '#92d050',
                    markerOptions: {style:'circle'}
                }]
            });

            $('.jqplot-xaxis-tick').each(function(i, elem) {
                // If the index is odd (1, 3, 5...), hide the label
                if (i % 4 !== 0) {
                    //$(this).hide();
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