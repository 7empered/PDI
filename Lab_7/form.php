<?php
session_start();

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $student = [
        'firstName'   => trim(filter_input(INPUT_POST, 'firstName',   FILTER_SANITIZE_STRING) ?: ''),
        'lastName'    => trim(filter_input(INPUT_POST, 'lastName',    FILTER_SANITIZE_STRING) ?: ''),
        'patronymic'  => trim(filter_input(INPUT_POST, 'patronymic', FILTER_SANITIZE_STRING) ?: ''),
        'gender'      => trim(filter_input(INPUT_POST, 'gender',     FILTER_SANITIZE_STRING) ?: ''),
        'group'       => trim(filter_input(INPUT_POST, 'group',      FILTER_SANITIZE_STRING) ?: ''),
    ];
    $valid = array_reduce($student, fn($ok, $v) => $ok && $v !== '', true);
    if ($valid) {
        $_SESSION['students'][] = $student;
    }
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'import' && !empty($_FILES['file']['tmp_name'])) {
    $txt = file_get_contents($_FILES['file']['tmp_name']);
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $imported = [];

    if ($ext === 'json') {
        $data = json_decode($txt, true);
        if (is_array($data)) $imported = $data;
    } elseif ($ext === 'xml') {
        $xml = simplexml_load_string($txt);
        foreach ($xml->student as $s) {
            $imported[] = [
                'firstName'  => (string)$s->firstName,
                'lastName'   => (string)$s->lastName,
                'patronymic' => (string)$s->patronymic,
                'gender'     => (string)$s->gender,
                'group'      => (string)$s->group,
            ];
        }
    }

    foreach ($imported as $stu) {
        if (isset($stu['firstName'], $stu['lastName'], $stu['gender'], $stu['group'])) {
            $_SESSION['students'][] = $stu;
        }
    }

    header('Location: index.php');
    exit;
}

if (isset($_GET['export'])) {
    $students = $_SESSION['students'];
    if ($_GET['export'] === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="students.json"');
        echo json_encode($students, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    if ($_GET['export'] === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="students.xml"');
        $xml = new SimpleXMLElement('<students/>');
        foreach ($students as $s) {
            $node = $xml->addChild('student');
            foreach ($s as $k => $v) {
                $node->addChild($k, htmlspecialchars($v, ENT_XML1|ENT_COMPAT, 'UTF-8'));
            }
        }
        echo $xml->asXML();
        exit;
    }
}