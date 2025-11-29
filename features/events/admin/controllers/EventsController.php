<?php

class EventsController {
    private $mysqli;
    private $uploadDir;

    public function __construct($mysqli, $rootPath) {
        $this->mysqli = $mysqli;
        $this->uploadDir = $rootPath . '/assets/uploads';
    }

    public function handleCreate() {
        $message = '';
        $messageClass = 'notice';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
            $time = !empty($_POST['event_time']) ? $_POST['event_time'] : null;
            $location = trim($_POST['location'] ?? '');
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            
            $gamba = null;
            // Handle file upload if provided
            if (!empty($_FILES['gamba']['name'])) {
                if (!is_dir($this->uploadDir)) { @mkdir($this->uploadDir, 0777, true); }
                $ext = pathinfo($_FILES['gamba']['name'], PATHINFO_EXTENSION);
                $basename = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . ($ext?'.'.preg_replace('/[^a-zA-Z0-9]+/','',$ext):'');
                $target = $this->uploadDir . '/' . $basename;
                if (move_uploaded_file($_FILES['gamba']['tmp_name'], $target)) {
                    $gamba = 'assets/uploads/' . $basename;
                }
            } else if (isset($_POST['gamba_url']) && $_POST['gamba_url'] !== '') {
                $gamba = trim($_POST['gamba_url']);
            }
            
            // Determine which columns exist in the events table and build INSERT accordingly
            $existingCols = [];
            $colRes = $this->mysqli->query("SHOW COLUMNS FROM events");
            if ($colRes) {
                while ($c = $colRes->fetch_assoc()) { $existingCols[] = $c['Field']; }
                $colRes->close();
            }

            // If title column exists, require title
            if (in_array('title', $existingCols) && $title === '') {
                $message = 'Title is required.';
                $messageClass = 'notice error';
            } else {
                $insertCols = [];
                $placeholders = [];
                $types = '';
                $values = [];

                if (in_array('title', $existingCols)) { $insertCols[] = 'title'; $placeholders[] = '?'; $types .= 's'; $values[] = $title; }
                if (in_array('description', $existingCols)) { $insertCols[] = 'description'; $placeholders[] = '?'; $types .= 's'; $values[] = $desc; }
                if (in_array('event_date', $existingCols)) { $insertCols[] = 'event_date'; $placeholders[] = '?'; $types .= 's'; $values[] = $date; }
                if (in_array('event_time', $existingCols)) { $insertCols[] = 'event_time'; $placeholders[] = '?'; $types .= 's'; $values[] = $time; }
                if (in_array('location', $existingCols)) { $insertCols[] = 'location'; $placeholders[] = '?'; $types .= 's'; $values[] = $location; }
                if (in_array('image_path', $existingCols)) { $insertCols[] = 'image_path'; $placeholders[] = '?'; $types .= 's'; $values[] = $gamba; }
                if (in_array('is_active', $existingCols)) { $insertCols[] = 'is_active'; $placeholders[] = '?'; $types .= 'i'; $values[] = $isActive; }

                if (empty($insertCols)) {
                    $message = 'No writable columns found in events table.';
                    $messageClass = 'notice error';
                } else {
                    $sql = 'INSERT INTO events (' . implode(', ', $insertCols) . ') VALUES (' . implode(', ', $placeholders) . ')';
                    $stmt = $this->mysqli->prepare($sql);
                    if ($stmt) {
                        // bind_param requires references
                        $bindParams = [];
                        $bindParams[] = & $types;
                        for ($i = 0; $i < count($values); $i++) { $bindParams[] = & $values[$i]; }
                        call_user_func_array([$stmt, 'bind_param'], $bindParams);
                        $stmt->execute();
                        $stmt->close();
                        $message = 'Event created successfully';
                        $messageClass = 'notice success';
                    } else {
                        $message = 'Database error: ' . $this->mysqli->error;
                        $messageClass = 'notice error';
                    }
                }
            }
        }

        return ['message' => $message, 'messageClass' => $messageClass];
    }

    public function getAllEvents() {
        $items = [];
        // Build SELECT dynamically based on existing columns to avoid SQL errors
        $existingCols = [];
        $colRes = $this->mysqli->query("SHOW COLUMNS FROM events");
        if ($colRes) {
            while ($c = $colRes->fetch_assoc()) { $existingCols[] = $c['Field']; }
            $colRes->close();
        }

        // Default set of columns we want to display (if present)
        $desired = ['id', 'title', 'description', 'event_date', 'event_time', 'location', 'image_path', 'is_active', 'created_at'];
        $selectCols = [];
        foreach ($desired as $c) { if (in_array($c, $existingCols)) { $selectCols[] = $c; } }

        if (empty($selectCols)) {
            return $items;
        }

        $sql = 'SELECT ' . implode(', ', $selectCols) . ' FROM events ORDER BY ' . (in_array('event_date', $selectCols) ? 'event_date DESC, ' : '') . 'id DESC';
        $res = $this->mysqli->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) { $items[] = $row; }
            $res->close();
        }
        return $items;
    }
}
