<?php

namespace ay\xhprof;

if(empty($_GET['xhprof']['query']['target'])) {
    throw new \Exception('Missing required parameters.');
}

$xhprof_api_obj	= new api($config['pdo']);

switch ($_GET['xhprof']['query']['target']) {
    case 'hosts': {
        header('Content-Type: application/json');
        
        if(!\ay\error_present()) {
            $hosts	= $xhprof_api_obj->getHosts($_GET['term']);
            
            echo json_encode($hosts);
        }
        
        break;
    }
    case 'uris': {
        header('Content-Type: application/json');
        
        $filter = array('host_id' => $_GET['xhprof']['query']['host_id']);
        
        if(!\ay\error_present()) {
            $hosts	= $xhprof_api_obj->getUris($_GET['term'], $filter);
            
            echo json_encode($hosts);
        }
        
        break;
    }
    case 'treemap': {
        header('Content-Type: application/json');
        
        $fn_metrics_column	= function ($name, $group, $a) {
            $a = format_metrics($a['metrics'][$group][$name], $name);
        
            return $a['raw'];
        };        
        
        $xhprof_data_obj	= new Data($config['pdo']);
        $request = $xhprof_data_obj->get($_GET['xhprof']['query']['request_id']);
        $xhprof_obj = new Model($request);
        $aggregated_stack = $xhprof_obj->getAggregatedStack();
        
        $treeData = array();
        $treeData['name'] = 'myrequest';
        $treeData['children'] = array();
        
        $classData = array();
        foreach($aggregated_stack as $i => $a)
        {
            $class = '';
            if(($pos = strpos($a['callee'], '::')) !== false) {
                $class = substr($a['callee'], 0, $pos);
            }
            $rowData['name'] = $a['callee'];
            $rowData['ct']   = $a['metrics']['ct']['raw'];
            $rowData['wt']   = $fn_metrics_column('wt', 'exclusive', $a);
            $rowData['cpu']  = $fn_metrics_column('cpu', 'exclusive', $a);
            $rowData['mu']   = $fn_metrics_column('mu', 'exclusive', $a);
            $rowData['pmu']  = $fn_metrics_column('pmu', 'exclusive', $a);

            if (!isset($classData[$class])) {
                $classData[$class] = array();
            }
            $classData[$class][] = $rowData;
        }
        
        foreach($classData as $class => $_data) {
            $child = array();
            $child['name'] = $class;
            $child['children'] = $_data;
            
            $treeData['children'][] = $child;
        }
        
        
        echo json_encode($treeData);
        
        break;
    }
    default : throw new \Exception('Invalid target.');
}

