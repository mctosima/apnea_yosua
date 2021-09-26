<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{
    public function store(Request $request){
        $validatedfile = $request->validate([
            'file' => 'required','mimes:csv'
           ]);
           $times = array();
           $hr = array();
           $o = array();
           $b = array();
           $x = array();
           $y = array();
           $z = array();
           $xyz = array();
           $apnea = 0;
           $movementx = 0;
           $movementy = 0;
           $movementz = 0;
           $movements = 0;
           $selisih = array();
           $breath = array();


           $handle = fopen($request->file('file'), "r");
           while (($row = fgetcsv($handle)) !== FALSE){
               array_push($times, $row[0]);
               array_push($hr, (int)$row[1]);
               array_push($o, (int)$row[2]);
               array_push($b, (float)$row[3]);
               array_push($x, (float)$row[4]);
               array_push($y, (float)$row[5]);
               array_push($z, (float)$row[6]);
               array_push($xyz, sqrt( pow((float)$row[4], 2) + pow((float)$row[5], 2) + pow((float)$row[6], 2) ));
           }

           $last_time = $times[0];
           $is_up = null;
           $is_risingx = "none";
           $is_risingy = "none";
           $is_risingz = "none";
           $is_risings = "none";


           for ($i=0; $i<sizeof($times); $i++ ){
            if ($i != 0){
                if($is_risingx == "none"){
                    if( $x[$i] - $x[$i-1] >= 0.15 ){
                        $is_risingx = "true";
                    } elseif($x[$i] - $x[$i-1] <= -0.15 ){
                        $is_risingx = "false";
                    }
                } elseif($is_risingx == "true"){
                    if($x[$i] - $x[$i-1] <= 0.15 ){
                        $is_risingx = "none";
                        $movementx++;
                    }
                } elseif($is_risingx == "false"){
                    if($x[$i] - $x[$i-1] >= -0.15 ){
                        $is_risingx = "none";
                        $movementx++;
                    }
                }

                if($is_risingy == "none"){
                    if( $y[$i] - $y[$i-1] >= 0.15 ){
                        $is_risingy = "true";
                    } elseif($y[$i] - $y[$i-1] <= -0.15){
                        $is_risingy = "false";
                    }
                } elseif($is_risingy == "true"){
                    if($y[$i] - $y[$i-1] <= 0.15){
                        $is_risingy = "none";
                        $movementy++;
                    }
                } elseif($is_risingy == "false"){
                    if($y[$i] - $y[$i-1] >= -0.15 ){
                        $is_risingy = "none";
                        $movementy++;
                    }
                }

                if($is_risingz == "none"){
                    if( $z[$i] - $z[$i-1] >= 0.15 ){
                        $is_risingz = "true";
                    } elseif($z[$i] - $z[$i-1] <= -0.15){
                        $is_risingz = "false";
                    }
                } elseif($is_risingz == "true"){
                    if($z[$i] - $z[$i-1] <= 0.15){
                        $is_risingz = "none";
                        $movementz++;
                    }
                } elseif($is_risingz == "false"){
                    if( $z[$i] - $z[$i-1] >= -0.15){
                        $is_risingz = "none";
                        $movementz++;
                    }
                }

                if($is_risings == "none"){
                    if( sqrt(pow($x[$i],2) + pow($y[$i],2) + pow($z[$i],2)) - sqrt(pow($x[$i-1],2) + pow($y[$i-1],2) + pow($z[$i-1],2)) >= 0.09 ){
                        $is_risings = "true";
                    } elseif(sqrt(pow($x[$i],2) + pow($y[$i],2) + pow($z[$i],2)) - sqrt(pow($x[$i-1],2) + pow($y[$i-1],2) + pow($z[$i-1],2)) <= -0.09){
                        $is_risings = "false";
                    }
                } elseif($is_risings == "true"){
                    if(sqrt(pow($x[$i],2) + pow($y[$i],2) + pow($z[$i],2)) - sqrt(pow($x[$i-1],2) + pow($y[$i-1],2) + pow($z[$i-1],2)) <= 0.09){
                        $is_risings = "none";
                        $movements++;
                    }
                } elseif($is_risings == "false"){
                    if( sqrt(pow($x[$i],2) + pow($y[$i],2) + pow($z[$i],2)) - sqrt(pow($x[$i-1],2) + pow($y[$i-1],2) + pow($z[$i-1],2)) >= -0.09){
                        $is_risings = "none";
                        $movements++;
                    }
                }

            }
            if($i == 1){
                if($b[$i-1]-$b[$i] > 0){
                    $is_up = false;
                    array_push($breath, 0);
                } else {
                    $is_up = true;
                    array_push($breath, 1);
                }
            }
            if($is_up == true && $i != 1 && $i != 0){
                if($b[$i-1]-$b[$i] >= 0.2){
                    array_push($selisih,strtotime($last_time) - strtotime($times[$i]));
                    $is_up = false;
                    array_push($breath, 0);
                    if(strtotime($last_time) - strtotime($times[$i]) <= -10 ){
                        $apnea++;
                    }
                    $last_time = $times[$i];
                } else {
                    array_push($breath, 1);
                }
            } elseif($is_up == false && $i != 1 && $i != 0){
                if($b[$i-1]-$b[$i] <= -0.2){
                    array_push($selisih,strtotime($last_time) - strtotime($times[$i]));
                    $is_up = true;
                    array_push($breath, 1);
                    if(strtotime($last_time) - strtotime($times[$i]) <= -10 ){
                        $apnea++;
                    }
                    $last_time = $times[$i];
                } else {
                    array_push($breath, 0);
                }
            }
        }
        $time = ((strtotime($times[0]) - strtotime($times[sizeof($times)-1]))/3600) * -1;
        $index = $apnea/$time;
        $status = null;
        if($index < 5){
            $status = "Normal";
        } elseif ($index < 15){
            $status = "Mild Sleep Apnea";
        } elseif ($index < 29){
            $status = "Moderate Sleep Apnea";
        } else {
            $status = "Severe Sleep Apnea";
        }

        //dd($apnea, $selisih);
        return view('grafik')
        ->with(array('time' => $time))
        ->with(array('times' => $times))
        ->with(array('apnea' => $apnea))
        ->with(array('movement' => $movementz))
        ->with(array('hr' => $hr))
        ->with(array('o' => $o))
        ->with(array('b' => $b))
        ->with(array('x' => $x))
        ->with(array('y' => $y))
        ->with(array('z' => $z))
        ->with(array('index' => $index))
        ->with(array('status' => $status));


        return redirect('')->with('message', 'File Has been uploaded successfully in laravel 10');
    }
}
