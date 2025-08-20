<?php
// Configuración de CORS para desarrollo local
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuración del correo
$to_email = "peraltaignacio@protonmail.com"; // Tu email de destino
$website_name = "Ignacio Peralta Portfolio";

// Verificar que sea una petición POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Obtener y sanitizar los datos del formulario
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validar que todos los campos estén completos
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
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
    if (mail($to_email, $email_subject, $email_body, implode("\r\n", $headers))) {
        // Email enviado exitosamente
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your message has been sent successfully. I\'ll get back to you soon.'
        ]);
    } else {
        // Error al enviar el email
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Sorry, there was an error sending your message. Please try again or contact me directly.'
        ]);
    }
} catch (Exception $e) {
    // Capturar cualquier excepción
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Sorry, there was an unexpected error. Please try again later.'
    ]);
}
?>
