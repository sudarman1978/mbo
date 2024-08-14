<?
### Invoice Aging
$query1 = $this->db->query("select datediff(now(),if(t0.fld_btdtsa < '1945-12-12',t0.fld_btdt,t0.fld_btdtsa)) 'aging', t0.*,
if(t0.fld_currency = 'IDR',format(t0.fld_btamt/1000000000,2),0) 'IDR',
if(t0.fld_currency = 'USD',t0.fld_btamt/1000000000,0) 'USD',
t0.fld_btno,
t0.fld_btflag,
t0.fld_btp01,
t1.fld_tyvalnm
from 
dnxapps.tbl_invoice t0
left join dnxapps.tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_btflag
where 
t0.fld_btamt01 < 1
and
t0.fld_btp02 != '107.087'
");
$idr= 0;
$idr1=0;
$usd1=0;
$idr2=0;
$usd2=0;
$idr3=0;
$usd3=0;
$idr4=0;
$usd4=0;
$idr5=0;
$usd5=0;

foreach ($query1->result() as $rviewrs) {
    if($rviewrs->aging > 0 && $rviewrs->aging <= 7) {
      $idr1 = $idr1 + $rviewrs->IDR;
      $usd1 = $usd1 + $rviewrs->USD;
    }
    if($rviewrs->aging > 7 && $rviewrs->aging <= 14) {
      $idr2 = $idr2 + $rviewrs->IDR;
      $usd2 = $usd2 + $rviewrs->USD;
    }
    if($rviewrs->aging > 14 && $rviewrs->aging <= 30) {
      $idr3 = $idr3 + $rviewrs->IDR;
      $usd3 = $usd3 + $rviewrs->USD;
    }
    if($rviewrs->aging > 30 && $rviewrs->aging <= 90) {
      $idr4 = $idr4 + $rviewrs->IDR;
      $usd4 = $usd4 + $rviewrs->USD;
    }
    if($rviewrs->aging > 90) {
      $idr5 = $idr5 + $rviewrs->IDR;
      $usd5 = $usd5 + $rviewrs->USD;
    }
    $idr = $idr + $rviewrs->IDR;
}
                                  
?>
<script type="text/javascript">
	var chart1; // globally available
	var chart2;
	var chart3;
	var chart4;
Highcharts.setOptions({
		lang: {
			numericSymbols: [ ' millions']
		}
	});
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'container',
            type: 'column'
         },   
	title: {
                text: 'Outstanding Invoice Aging'
            },
            xAxis: {
                categories: ['1 - 7 days', '7 - 14 days', '14 - 30 days', '30 - 90 days', 'Over 90 days']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Invoice Amount in millions'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -70,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: 'IDR',
                data: [<?=$idr1;?>, <?=$idr2;?>,<?=$idr3;?>,<?=$idr4;?>,<?=$idr5;?>]
            }]
        });
    });
         
</script>
	</head>
	<body>
	<div class='dashboard_wrap'>
	<div class='dashboard_left'>
          <fieldset style="height: 290px;">
            <legend>Invoice Aging</legend>
            <div id='container'></div>
          </fieldset>
        </div>	
        <div class='dashboard_right'>
	  <fieldset style="height: 290px;">
          <legend>Trucking Profit</legend>
          </fieldset>
        </div>
	</div>
	</body>
</html>

