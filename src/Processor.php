<?php
namespace Arostech\Analytics;

use App\Models\Request;


class Processor{

    public static function test(){
        echo 'i am the processor - now updated third time!';
    }

    
    
    public static function getProcessedAnalytics($data){
        try {

            $analyticsShell = Processor::setupPrimaryStatistics();

            $analytics = Processor::loopThroughData($data, $analyticsShell);

            return $analytics;



        } catch (\Throwable $th) {
            // dd($th);
            return $th;
        }
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

    public static function loopThroughData($data, $analyticsShell){

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