<?php 
set_time_limit(500); 
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
require_once('database.php');

global $data;
global $memory;
global $cpu;
global $hdd;

#$_POST['period'] = 30;
#$_POST['artifact'] = "NzY1ODFjZjQ4YTJmNzg=";


if(!isset($_POST['period']) && intval($_POST['period'])<=1)
{
    json_encode(['Status'=>400,'msg'=>'O periodo não pode ser igual a 1']); die();
}



try
{    
    $SQL="SELECT n.* FROM nucreport n  LEFT JOIN  artifact_x_teamviewer t ON n.artifact=t.artifact  
          WHERE n.artifact=?  OR t.teamviewer=?  
          ORDER BY n.datahora DESC LIMIT " .intval($_POST['period']);
    $conn = connect();
    $stmt = $conn->prepare($SQL);
    $stmt->execute([@$_POST['artifact'], @$_POST['teamviewer']]);
    $data =$stmt->fetchAll(\PDO::FETCH_ASSOC);  
    if(!empty($data))
    {
        $mem = [];
        $cpu = [];
        $hdd = [];
        $dataHora =[];
        $temp = [];

        for($i=0; $i<count($data); $i++ )
        {
            array_push($mem,$data[$i]['memoria']);
            array_push($cpu, $data[$i]["cpu"]);
            array_push($hdd, $data[$i]["hdd"]);
            array_push($dataHora, $data[$i]["datahora"]);
            array_push($temp, $data[$i]["temperature"]);
        }
        #########################--MEMORY--######################
        global $maxMemory;
        global $useMemory;    
        global $freeMemory;

        $useMemory=[];
        $freeMemory=[];

        for($i=0; $i< count($mem); $i++)
        {
            $mem[$i]= preg_replace('/total:/','',$mem[$i]);
            $mem[$i]= preg_replace('/usada:/','',$mem[$i]);sleep(1);
            $mem[$i]= preg_replace('/livre:/','',$mem[$i]);
            $maxMemory =substr($mem[0], 0, 5);
            if($maxMemory != substr($mem[$i], 0, 5))
            {
                $maxMemory = substr($mem[$i], 0, 5);
            }
        }

        for($i=0; $i<count($mem); $i++)
        {
            $mem[$i] = preg_replace("/".$maxMemory."/",'' , $mem[$i]);
            $mem[$i] =  ltrim($mem[$i], ' ');
        }

        for($i=0; $i<count($mem); $i++)
        {
            $mem[$i] =  explode(' ',$mem[$i]); sleep(1);
        }
    
        for($i=0; $i<count($mem); $i++)
        {
            array_push( $useMemory, intval($mem[$i][0]));
            array_push( $freeMemory, intval($mem[$i][2]));
        }
        #########################--END--MEMORY--######################
        #########################--CPU--##############################

        for($i=0; $i<count($cpu); $i++)
        {
            $cpu[$i]= preg_replace('/load:/','',$cpu[$i]);
            $cpu[$i]= preg_replace('/iddle:/','',$cpu[$i]);
            $cpu[$i] = trim($cpu[$i]);
        }

        for($i=0; $i<count($cpu); $i++)
        {
            $cpu[$i] =  explode(' ',$cpu[$i]);
        }
        global $loadCPU;
        global $freeCPU;

        $loadCPU=[];
        $freeCPU =[];
        if(!empty($cpu))
        {
            for($i=0; $i<count($cpu); $i++)
            {
                array_push( $loadCPU, floatval($cpu[$i][0]));
                array_push( $freeCPU, floatval(@$cpu[$i][2]));
            }
        }

        #########################--END--CPU--##############################
        ######################### --HDD-- #################################

        for($i=0; $i<count($hdd); $i++)
        {
            $hdd[$i]= preg_replace('/hd usage /','',$hdd[$i]);
            $hdd[$i]= preg_replace('/%/','',$hdd[$i]);
        }
        ######################### --END--HDD-- ##############################
        ######################### --TEMP-- ##################################
        global $tempeReport;
        $tempeReport=[];
        for($i=0; $i< sizeof($temp); $i++)
        {
            $tempeReport[$i]= htmlentities($temp[$i]);
        }
        #########################  --END--TEMP-- ############################
        ######################### --DATAHORA-- ##############################
        for($i=0; $i<count($dataHora); $i++)
        {
            $phpdate = strtotime( $dataHora[$i] );
            #$mysqldate = date( 'Y-m-d H:i:s', $phpdate );
            $mysqldate = date( 'd-m-Y H:i', $phpdate );

            $dataHora[$i] = $mysqldate;
        }
        ######################### --END--DATAHORA-- ###########################
        ######################### --HARDWARE-- ##############################
        // SELECT n.*, t.* FROM artifact_x_hardware n   JOIN 
        // artifact_x_teamviewer t ON n.artifact=t.artifact 
        // WHERE n.artifact='' OR t.teamviewer='1645586616'

        $SQL = "SELECT n.*, t.* FROM artifact_x_hardware n JOIN artifact_x_teamviewer t
                ON n.artifact=t.artifact  
                WHERE n.artifact= ? OR t.teamviewer=? "; 
        $stmt = $conn->prepare($SQL);
        $stmt->execute([@$_POST['artifact'], @$_POST['teamviewer']]);
        $hwinfo =$stmt->fetchAll(\PDO::FETCH_ASSOC);  
        if(!empty($hwinfo))
        {
            $hw = json_encode(($hwinfo[0]), JSON_PRETTY_PRINT   );
            $hw = stripslashes($hw);    
            $hw = stripslashes($hw);
            $hw = htmlentities($hw);
        }
        else $hwinfo='ainda não informado pelo deploy';

        echo json_encode(['Status'=>202,'dates'=> $dataHora ,
                                         'maxMemory'=> $maxMemory ,
                                         'useMemory'=>$useMemory,
                                         'freeMemory'=>$freeMemory, 
                                         'hdd'=>$hdd, 
                                         'freeCPU'=>$freeCPU,
                                         'loadCPU'=>$loadCPU,
                                         'temp'=>$tempeReport,
                                         'hwinfo'=>$hw ]); exit;

    }
    else json_encode(['Status'=>400,'msg'=>'Sem dados para este artefato']); die(); 
}
catch(\PDOException $e)
{
    var_dump($e->getMessage());
}
catch(Exception $er)
{
    var_dump($er->getMessage());
}



function utf8_str_split(string $input, int $splitLength = 1)
{
    $re = \sprintf('/\\G.{1,%d}+/us', $splitLength);
    \preg_match_all($re, $input, $m);
    return $m[0];
}



?>