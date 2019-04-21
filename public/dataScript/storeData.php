<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'storeCategory':
        $response = storeCategory($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storeImpact':
        $response = storeImpact($mysql, $dataUsage->_name, $dataUsage->_description, $dataUsage->_importance, $dataUsage->_color);
        break;
    case 'storePriority':
        $response = storePriority($mysql, $dataUsage->_name, $dataUsage->_description, $dataUsage->_importance, $dataUsage->_color);
        break;
    case 'storeStatus':
        $response = storeStatus($mysql, $dataUsage->_name, $dataUsage->_description, $dataUsage->_override, $dataUsage->_color);
        break;
    case 'storeTask':
        $response = storeTask($mysql,
            $dataUsage->_name,
            $dataUsage->_description,
            $dataUsage->_status,
            $dataUsage->_assignedUser,
            $dataUsage->_assignedByUser,
            $dataUsage->_impact,
            $dataUsage->_category,
            $dataUsage->_priority,
            $dataUsage->_comments);
        break;
}
echoResponse();
function storeCategory(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            category(name, description, created_at, updated_at) 
            VALUES ('$name', '$description', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storeImpact(MySQLConnection $mysql, $name, $description, $importance, $color) {
    $query = "INSERT INTO 
            impact(name, description, importance, color, created_at, updated_at) 
            VALUES ('$name', '$description', $importance, '$color', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storePriority(MySQLConnection $mysql, $name, $description, $importance, $color) {
    $query = "INSERT INTO 
            priority(name, description, created_at, updated_at) 
            VALUES ('$name', '$description', $importance, '$color',  now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storeStatus(MySQLConnection $mysql, $name, $description, $override, $color) {
    $query = "INSERT INTO 
            status(name, description, override, color, created_at, updated_at) 
            VALUES ('$name', '$description', $override, '$color',  now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}

function storeTask(MySQLConnection $mysql,
            $name,
            $description,
            $status,
            $assignedUser,
            $assignedByUser,
            $impact,
            $category,
            $priority,
            $comments) {
    $query = "INSERT INTO 
            task(name, description, status_id, assigned_user_id, assigned_by_user_id, impact_id, category_id, priority_id, comments, time_to_solve, created_at, updated_at) 
            VALUES ('$name', '$description', $status, $assignedUser, $assignedByUser, $impact, $category, $priority, '$comments', 0, now(), now())";
    $object = null;
    try {
        $mysql->query($query);
        $lastId =  $mysql->connection->insert_id;
        $query = "SELECT 
        t.id AS id, 
        t.name AS name, 
        t.description AS description, 
        s.name AS status, 
        au.name AS assigned_user, 
        ab.name AS assigned_by_user, 
        i.name AS impact, 
        c.name AS category, 
        p.name AS priority, 
        t.comments AS comments, 
        t.time_to_solve AS time_to_solve, 
        t.created_at AS created_at, 
        t.updated_at AS updated_at, 
        t.deleted_at AS deleted_at 
        FROM task t 
        INNER JOIN status s ON s.id = t.status_id 
        INNER JOIN users au ON au.id = t.assigned_user_id 
        INNER JOIN users ab ON ab.id = t.assigned_by_user_id 
        INNER JOIN impact i ON i.id = t.impact_id 
        INNER JOIN category c ON c.id = t.category_id 
        INNER JOIN priority p ON p.id = t.priority_id 
        WHERE t.deleted_at IS NULL
        AND t.id = $lastId";
        var_dump($query);
        try {
            $result = $mysql->query($query);
            if ($result->num_rows > 0) {
                $line = $result->fetch_row();
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
                    'comments' => $line[9],
                    'time_to_solve' => $line[10],
                    'created_at' => $line[11],
                    'update_at' => $line[12],
                    'deleted_at' => $line[12],
                ];
                return $object;
            }

        }catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }catch (Exception $e) {
        var_dump($e->getMessage());
    }
    return $object;
}
