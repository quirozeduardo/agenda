<?php
require 'DB.php';
require '../Export/PhpExcelHelperFormat.php';
$mysql = new MySQLConnection();
ob_get_clean();
$section = $_GET['export'];
$dataUsage = json_decode($_GET['filters']);
switch ($section) {
    case 'exportTasks':
        $response = retrieveTasks($mysql, $dataUsage->filterStatus, $dataUsage->filterPriority, $dataUsage->filterImpact, $dataUsage->filterCategory, $dataUsage->filterDepartment, $dataUsage->user);
        downloadTasks($response);
        break;
}

function downloadTasks($response) {
    $headers = new \PhpExcelHelperFormat\Header([
        new \PhpExcelHelperFormat\CellHeader('#Act', 'A', 20),
        new \PhpExcelHelperFormat\CellHeader('Name', 'B', 20),
        new \PhpExcelHelperFormat\CellHeader('Description', 'C', 20),
        new \PhpExcelHelperFormat\CellHeader('Status', 'D', 20),
        new \PhpExcelHelperFormat\CellHeader('Assigned User', 'E', 30),
        new \PhpExcelHelperFormat\CellHeader('Assigned by', 'F', 30),
        new \PhpExcelHelperFormat\CellHeader('Impact', 'G', 20),
        new \PhpExcelHelperFormat\CellHeader('Category', 'H', 20),
        new \PhpExcelHelperFormat\CellHeader('Priority', 'I', 20),
        new \PhpExcelHelperFormat\CellHeader('Department', 'J', 20),
        new \PhpExcelHelperFormat\CellHeader('Comments', 'K', 20),
        new \PhpExcelHelperFormat\CellHeader('Time to Solve', 'L', 20),
        new \PhpExcelHelperFormat\CellHeader('Created At', 'M', 20),
        new \PhpExcelHelperFormat\CellHeader('Updated At', 'N', 20),
    ]);
    $excelHelper = new \PhpExcelHelperFormat\PhpExcelHelperFormat(null, $headers);
    $count = 1;
    foreach ($response as $task) {
        $row = new \PhpExcelHelperFormat\Row([
            new \PhpExcelHelperFormat\Cell($task['id'], 'A'),
            new \PhpExcelHelperFormat\Cell($task['name'], 'B'),
            new \PhpExcelHelperFormat\Cell($task['description'], 'C'),
            new \PhpExcelHelperFormat\Cell($task['status'], 'D'),
            new \PhpExcelHelperFormat\Cell($task['assigned_user'], 'E'),
            new \PhpExcelHelperFormat\Cell($task['assigned_by'], 'F'),
            new \PhpExcelHelperFormat\Cell($task['impact'], 'G'),
            new \PhpExcelHelperFormat\Cell($task['category'], 'H'),
            new \PhpExcelHelperFormat\Cell($task['priority'], 'I'),
            new \PhpExcelHelperFormat\Cell($task['department'], 'J'),
            new \PhpExcelHelperFormat\Cell($task['comments'], 'K'),
            new \PhpExcelHelperFormat\Cell($task['time_to_solve'], 'L'),
            new \PhpExcelHelperFormat\Cell($task['created_at'], 'M'),
            new \PhpExcelHelperFormat\Cell($task['update_at'], 'N'),
        ]);
        $excelHelper->insertRow($row);
        $count++;
    }
    $cellStyleDefault = array(
        'font' => array(
            'name' => 'Arial',
            'size' => 10,
            'color' => array('rgb' => '000000')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'wrap' => true
        )
    );

    $cordinateStyle = "A1:N".$count;
    $excelHelper->addStyle($cordinateStyle, $cellStyleDefault);
    $excelHelper->downloadAsXLSX('REPORT_TASKS');
}

function retrieveTasks(MySQLConnection $mysql, $filterStatus, $filterPriority, $filterImpact, $filterCategory, $department, $user) {


    $query = "SELECT t.id as id,
        t.name as name,
        t.description as description,
        sta.name as status,
        CONCAT(ua.name, ' ', ua.last_name) as assigned_user,
        CONCAT(ub.name, ' ', ub.last_name) as assigned_by_user,
        im.name as impact,
        cat.name as category,
        prio.name as priority,
        dep.name as department,
        t.comments as comments,
        t.time_to_solve as time_to_solve,
        t.created_at as created_at,
        t.updated_at as updated_at,
        t.deleted_at as deleted_at
        FROM task t
        INNER JOIN status sta ON sta.id = t.status_id
        INNER JOIN users ua ON ua.id = t.assigned_user_id
        INNER JOIN users ub ON ub.id = t.assigned_by_user_id
        INNER JOIN impact im ON im.id = t.impact_id
        INNER JOIN category cat ON cat.id = t.category_id
        INNER JOIN priority prio ON prio.id = t.priority_id
        INNER JOIN department dep ON dep.id = t.department_id
        INNER JOIN user_departments ud ON t.department_id = ud.department_id 
        WHERE t.deleted_at IS NULL
        AND t.status_id ".((intval($filterStatus)==-1)?'!=':'=')." $filterStatus
        AND t.priority_id ".((intval($filterPriority)==-1)?'!=':'=')." $filterPriority
        AND t.impact_id ".((intval($filterImpact)==-1)?'!=':'=')." $filterImpact
        AND t.category_id ".((intval($filterCategory)==-1)?'!=':'=')." $filterCategory";

    if ($user !== null) {
        $query .= " AND ud.user_id = $user->_id";
    }else {
        $query .= " AND ud.user_id = 0";
    }
    if ($department != 0 && $department!= '0') {
        $query .= " AND t.department_id=$department";
    }
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2],
                'status' => $line[3],
                'assigned_user' => $line[4],
                'assigned_by' => $line[5],
                'impact' => $line[6],
                'category' => $line[7],
                'priority' => $line[8],
                'department' => $line[9],
                'comments' => $line[10],
                'time_to_solve' => $line[11],
                'created_at' => $line[12],
                'update_at' => $line[13],
                'deleted_at' => $line[14],
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
        var_dump($e->getMessage());
    }
    return $objects;
}