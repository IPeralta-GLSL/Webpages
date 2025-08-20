<?php
// Configuración de CORS más completa
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

// Log para debugging - capturar toda la información de la petición
error_log("=== DEBUGGING EMAIL FORM ===");
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Content-Type: " . (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'not set'));
error_log("Raw POST data: " . file_get_contents('php://input'));
error_log("_POST array: " . print_r($_POST, true));

// Manejar peticiones OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    error_log("Handling OPTIONS request");
    http_response_code(200);
    exit();
}

// Configuración del correo
$to_email = "peraltaignacio@protonmail.com"; // Tu email de destino
$website_name = "Ignacio Peralta Portfolio";

// Verificar que sea una petición POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    error_log("Method not allowed. Received: " . $_SERVER["REQUEST_METHOD"]);
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Received: ' . $_SERVER["REQUEST_METHOD"]]);
    exit();
}

// Obtener datos del formulario - manejar tanto FormData como JSON
$input_data = null;
$name = '';
$email = '';
$subject = '';
$message = '';

// Intentar obtener datos de $_POST primero (FormData)
if (!empty($_POST)) {
    error_log("Using _POST data");
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
} else {
    // Si no hay datos en $_POST, intentar JSON
    error_log("_POST is empty, trying to parse JSON input");
    $raw_input = file_get_contents('php://input');
    error_log("Raw input: " . $raw_input);
    
    if (!empty($raw_input)) {
        $input_data = json_decode($raw_input, true);
        if ($input_data) {
            error_log("Successfully parsed JSON: " . print_r($input_data, true));
            $name = isset($input_data['name']) ? trim($input_data['name']) : '';
            $email = isset($input_data['email']) ? trim($input_data['email']) : '';
            $subject = isset($input_data['subject']) ? trim($input_data['subject']) : '';
            $message = isset($input_data['message']) ? trim($input_data['message']) : '';
        } else {
            error_log("Failed to parse JSON input");
        }
    }
}

// Log para debugging (remover en producción)
error_log("POST data received: " . print_r($_POST, true));

// Validar que todos los campos estén completos
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'All fields are required',
        'debug' => [
            'name' => !empty($name),
            'email' => !empty($email),
            'subject' => !empty($subject),
            'message' => !empty($message)
        ]
    ]);
    exit();
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

// Sanitizar datos para prevenir inyección de headers
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Crear el subject del email
$email_subject = "[" . $website_name . "] " . $subject;

// Crear el contenido del email
$email_body = "
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Contact Form Message</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #2c3e50; }
        .value { margin-top: 5px; padding: 10px; background-color: white; border-left: 4px solid #3498db; }
        .footer { text-align: center; padding: 20px; color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Message</h2>
            <p>From: {$website_name}</p>
        </div>
        
        <div class='content'>
            <div class='field'>
                <div class='label'>Name:</div>
                <div class='value'>{$name}</div>
            </div>
            
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'>{$email}</div>
            </div>
            
            <div class='field'>
                <div class='label'>Subject:</div>
                <div class='value'>{$subject}</div>
            </div>
            
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br($message) . "</div>
            </div>
        </div>
        
        <div class='footer'>
            <p>This message was sent from the contact form on {$website_name}</p>
            <p>Sent on: " . date('Y-m-d H:i:s') . "</p>
        </div>
    </div>
</body>
</html>
";

// Headers del email
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: ' . $website_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
    'Reply-To: ' . $name . ' <' . $email . '>',
    'X-Mailer: PHP/' . phpversion()
);

// Intentar enviar el email
try {
    // Verificar que la función mail esté disponible
    if (!function_exists('mail')) {
        throw new Exception('Mail function is not available on this server');
    }
    
    if (mail($to_email, $email_subject, $email_body, implode("\r\n", $headers))) {
        // Email enviado exitosamente
        error_log("Email sent successfully to: " . $to_email);
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your message has been sent successfully. I\'ll get back to you soon.'
        ]);
    } else {
        // Error al enviar el email
        $last_error = error_get_last();
        error_log("Mail function failed. Last error: " . print_r($last_error, true));
        error_log("Mail function parameters - To: $to_email, Subject: $email_subject");
        
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Sorry, there was an error sending your message. Please try again or contact me directly.',
            'debug' => [
                'error' => 'Mail function returned false',
                'last_error' => $last_error,
                'mail_available' => function_exists('mail')
            ]
        ]);
    }
} catch (Exception $e) {
    // Capturar cualquier excepción
    error_log("Exception in send_email.php: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Sorry, there was an unexpected error. Please try again later.',
        'debug' => $e->getMessage()
    ]);
}
?>
