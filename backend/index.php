<?php
require_once 'src/util/config.php';
require_once 'src/Controller/ContactController.php';
use agenda_de_contato_php\Controller\ContactController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'];
    $email = $data['email'];
    $phone = !empty($data['phone']) ? $data['phone'] : '81999999999';
    $smartphone = !empty($data['smartphone']) ? $data['smartphone'] : '81999999999';
    $street = $data['street'];
    $city = $data['city'];
    $state = $data['state'];
    $cep = $data['cep'];

    $contactController = new ContactController;
    $result = $contactController->createContact($name, $email, $phone, $smartphone, $street, $city, $state, $cep);

    echo json_encode($result);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $contactController = new ContactController;
    $contacts = $contactController->getAllContacts();

    echo json_encode($contacts);
}



elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $contactIdToDelete = end($urlParts);

    if (!empty($contactIdToDelete)) {
        $contactController = new ContactController;
        $result = $contactController->deleteContact($contactIdToDelete);

        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID do contato não fornecido']);
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $contactIdToUpdate = end($urlParts);

    if (!empty($contactIdToUpdate)) {
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['name'];
        $email = $data['email'];
        $phone = !empty($data['phone']) ? $data['phone'] : '81999999999';
        $street = $data['street'];
        $city = $data['city'];
        $state = $data['state'];
        $cep = $data['cep'];

        $contactController = new ContactController;
        $result = $contactController->updateContact($contactIdToUpdate, $name, $email, $phone, $street, $city, $state, $cep);

        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID do contato não fornecido']);
    }
}




