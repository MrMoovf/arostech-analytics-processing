<?php
namespace Arostech\Analytics;



class Processor{

    public static function test(){
        echo 'i am the processor - now updated FOURTH time!';
    }


    
    public static function getProcessedAnalytics($data){
        try {

            $analyticsShell = Processor::setupPrimaryStatistics();

            $analytics = Processor::clicksAndUniqueUsers($data, $analyticsShell);

            return $analytics;



        } catch (\Throwable $th) {
            return $th;
        }
    }

    public static function calcMovingAverage($data, $timeFormat, $dataType, $windowWidth){
        // Insert empty spaces to array matching the time window width: 7 days
        $result = [];
        for($i = 0; $i < $windowWidth-1; $i++){
            array_push($result,[]);
        }

        // Get data
        $data = $data[$timeFormat][$dataType];


        // Gather window array


        // Calculate average
        $windows = [];
        for($i = 0; $i < count($data) - $windowWidth + 1; $i++){
            $window = array_slice($data,$i,$windowWidth);
            array_push($windows, $window);
            // dd($window);
            $sum = array_reduce($window,'self::addYValues',0);
            $time = end($window)['x'];
            $average = round($sum / $windowWidth,2);

            $xy = [
                'x' => $time,
                'y' => $average
            ];

            array_push($result,$xy);
        }

        dd('this is clicks over days',$data, 'this is result array',$result, 'this is windowed arrays to loop', $windows,'result', $result);



        // Insert into array

        // Repeat, sliding the window over to next day, inserting into array

        // Repeat for all days

        // Result should be array starting at the 7th day

    }

    public static function addYValues($sum, $point){
        // dd($sum, $point['y']);
        return $sum + $point['y'];

    }

    public static function setupPrimaryStatistics(){

        $measurements = [
            'clicks' => [], 
            'unique_visitors' => []
        ];
        $analyticsShell = [
                'years' => $measurements,
                'months' => $measurements,
                'days' => $measurements,
                'hours' => $measurements,
        ];

        return $analyticsShell;
    }

    public static function clicksAndUniqueUsers($data, $analyticsShell){

        // Setting Unique visitors
        $unique_visitors = [];

        $clicks = $analyticsShell;
        foreach ($data as $row) {
            $createdAt = $row['created_at'];
            $visitorId = $row['visitor_id'];

            foreach ($clicks as $timeFormat => $value) {
                
                // Setting time
                $time = '';
                switch ($timeFormat) {
                    case 'years':
                        $time = substr($createdAt,0,4);
                        break;
                    case 'months':
                        $time = substr($createdAt,0,7);
                        break;
                    case 'days':
                        $time = substr($createdAt,0,10);
                        break;
                    case 'hours':
                        $time = substr($createdAt,11,2);
                        break;

                    
                    default:
                        # code...
                        break;
                }

                // Is the time/x there?
                $xIsThere = false;

                foreach ($clicks[$timeFormat]['clicks'] as $index => $xyPoints) {
                    $x = $xyPoints['x'];
                    $y = $xyPoints['y'];

                    if($x == $time){
                        $xIsThere = true;
                        $clicks[$timeFormat]['clicks'][$index]['y']++;
                        if(!in_array($visitorId, $clicks[$timeFormat]['unique_visitors'][$index])){
                            array_push($clicks[$timeFormat]['unique_visitors'][$index], $visitorId);
                        }

                    }
                }
                if(!$xIsThere){
                    array_push($clicks[$timeFormat]['clicks'] ,['x' => $time, 'y' => 1]);
                    array_push($clicks[$timeFormat]['unique_visitors'], [$visitorId]);

                }
            }
        }

        foreach ($clicks as $timeFormat => $measurements) {
            $x = '';
            $y = '';
            $unique_visitors = [];
            foreach ($measurements['clicks'] as $datapoint => $value) {
                $x = $value['x'];
                $y = count($measurements['unique_visitors'][$datapoint]);
                array_push($unique_visitors,['x' => $x, 'y' => $y]);
            }
            $clicks[$timeFormat]['unique_visitors'] = $unique_visitors; 
        }

        return $clicks;
    }


}