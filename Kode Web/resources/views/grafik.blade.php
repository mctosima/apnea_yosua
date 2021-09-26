<!DOCTYPE html>
<html>
<head>
    <title>Sleep Apnea Classification</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href={{ URL::asset('css/app.css') }} rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        #diagnosa {
          background-color: #ffffff;
          width: fit-content;
          border: 15px solid #eeeeee;
          padding: 50px;
          margin:auto;
          text-align: center;


        }


        </style>

</head>
<body>
    <header class="text-black text-center">
        <h1 class="display-4">Sleep Apnea Classification</h1>

    </header>

</body>
<br><br>
<div class="container">
    <div class="row">
      <div class="col-sm-2 ">
          <div class="pt-2">
            <button type="button" class="btn btn-primary link" onclick="graph_breath()">Breath</button>
          </div>
          <div class="pt-2">
            <button type="button" class="btn btn-primary link" onclick="graph_spo2()">SPO2</button>
          </div>
          <div class="pt-2">
            <button type="button" class="btn btn-primary link" onclick="graph_heartrate()">Heart rate</button>
          </div>
          <div class="pt-2">
            <button type="button" class="btn btn-primary link"onclick="movement_graph()">Movement</button>
          </div>
      </div>
      <div class="col-sm-10">
            <div id="grafik"></div>
            <img
        src="{{url('/pic/mark.svg')}}"
        width="25" height="25"
        title="1 = exhale 0 = inhale"
        alt="hint"
        />
      </div>

    </div>
</div>
<br>
<div id="diagnosa"></div>



<script type="text/javascript">
    var time = @json($time);
    var movement = @json($movement);
    var index = @json($index);
    var status = @json($status);
    var hr = @json($hr);
    var breath = @json($b);
    var key = @json($times);
    var apnea = @json($apnea);
    var x = @json($x);
    var y = @json($y);
    var z = @json($z);
    var o = @json($o);

    graph_breath();

    document.getElementById("diagnosa").innerHTML = "Anda terdiagnosa " + "<b>" +status + "</b>" + " dengan indeks AHI sebesar "
    + index.toFixed(2) + ".<br> Tercatat terjadi sebanyak " + apnea + " kali henti napas dan melakukan gerakan sebanyak "+ movement +" kali dengan durasi sleep test selama "+ time.toFixed(2) + " jam ";
    document.getElementById("movement").innerHTML = movement;
    document.getElementById("apnea").innerHTML = apnea;
    document.getElementById("AHI").innerHTML = index + "<br>" + status;



    function graph_breath(){
        graph(breath,"Breath");
    }
    function graph_spo2(){
        graph(o,"SPo2");
    }
    function graph_heartrate(){
        graph(hr,"Heart Rate");
    }

    function graph(data, type){
    Highcharts.chart('grafik', {
       chart: {
         zoomType: 'x'
       },
       title: {
         text: "Breath Cycle"
       },
       subtitle: {
         text: document.ontouchstart === undefined ?
           'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       },
       xAxis: {
         categories : key
       },
       yAxis: {
         title: {
           text: type
         }
       },
       legend: {
         enabled: true
       },
       plotOptions: {
         area: {
           fillColor: {
             linearGradient: {
               x1: 0,
               y1: 0,
               x2: 0,
               y2: 1
             },
             stops: [
               [0, Highcharts.getOptions().colors[0]],
               [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
             ]
           },
           marker: {
             radius: 2
           },
           lineWidth: 1,
           states: {
             hover: {
               lineWidth: 1
             }
           },
           threshold: null
         }
       },

       series: [{
         name: type,
         data: data
       }
      ]
     });
}

    function movement_graph(){
        Highcharts.chart('grafik', {
       chart: {
         zoomType: 'x'
       },
       title: {
         text: "Movement"
       },
       subtitle: {
         text: document.ontouchstart === undefined ?
           'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       },
       xAxis: {
         categories : key
       },
       yAxis: {
         title: {
           text: "movement axis"
         }
       },
       legend: {
         enabled: true
       },
       plotOptions: {
         area: {
           fillColor: {
             linearGradient: {
               x1: 0,
               y1: 0,
               x2: 0,
               y2: 1
             },
             stops: [
               [0, Highcharts.getOptions().colors[0]],
               [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
             ]
           },
           marker: {
             radius: 2
           },
           lineWidth: 10,
           states: {
             hover: {
               lineWidth: 1
             }
           },
           threshold: null
         }
       },

       series: [{
         name: "x",
         data: x,
       },
       {
         name: "y",
         data: y,
       },
       {
         name: "z",
         data: z,
       }
      ]
     });
    }

</script>
</html>
