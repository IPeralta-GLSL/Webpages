<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar peticiones OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Configuración
$experience_file = __DIR__ . '/experience.json';
$backup_dir = __DIR__ . '/backups/';
$upload_password = 'tu_password_seguro_aqui'; // Cambia esto por una contraseña segura

// Crear directorio de backups si no existe
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

// Función para validar y procesar datos de LinkedIn
function processLinkedInData($data) {
    // Si es un export directo de LinkedIn, convertir al formato esperado
    if (isset($data['profile']) || isset($data['experiences'])) {
        return convertLinkedInExport($data);
    }
    
    // Si ya está en nuestro formato, validar
    if (is_array($data) && isset($data[0]['position'])) {
        return validateExperienceData($data);
    }
    
    throw new Exception('Invalid data format');
}

function convertLinkedInExport($linkedinData) {
    $experience = [];
    $id = 1;
    
    // Manejar diferentes formatos de export de LinkedIn
    $positions = [];
    
    if (isset($linkedinData['experiences'])) {
        $positions = $linkedinData['experiences'];
    } elseif (isset($linkedinData['positions'])) {
        $positions = $linkedinData['positions'];
    } elseif (isset($linkedinData['experience'])) {
        $positions = $linkedinData['experience'];
    }
    
    foreach ($positions as $position) {
        // Extraer información básica
        $title = $position['title'] ?? $position['position'] ?? 'Unknown Position';
        $company = $position['company'] ?? $position['companyName'] ?? 'Unknown Company';
        $location = $position['location'] ?? $position['area'] ?? 'Remote';
        
        // Procesar fechas
        $startDate = $position['startDate'] ?? $position['start_date'] ?? '';
        $endDate = $position['endDate'] ?? $position['end_date'] ?? 'Present';
        
        // Formatear fechas si vienen en formato de LinkedIn
        if (is_array($startDate)) {
            $startDate = ($startDate['year'] ?? '') . (isset($startDate['month']) ? '-' . str_pad($startDate['month'], 2, '0', STR_PAD_LEFT) : '');
        }
        if (is_array($endDate)) {
            $endDate = ($endDate['year'] ?? '') . (isset($endDate['month']) ? '-' . str_pad($endDate['month'], 2, '0', STR_PAD_LEFT) : '');
        }
        
        $duration = $startDate . ' - ' . $endDate;
        
        // Descripción
        $description = $position['description'] ?? $position['summary'] ?? '';
        
        // Tecnologías (si están disponibles)
        $technologies = [];
        if (isset($position['skills'])) {
            $technologies = is_array($position['skills']) ? $position['skills'] : [$position['skills']];
        }
        
        $experience[] = [
            'id' => $id++,
            'position' => $title,
            'company' => $company,
            'location' => $location,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'duration' => $duration,
            'description' => $description,
            'achievements' => [], // Se puede llenar manualmente después
            'technologies' => $technologies
        ];
    }
    
    return $experience;
}

function validateExperienceData($data) {
    $required_fields = ['position', 'company', 'duration', 'description'];
    
    foreach ($data as $item) {
        foreach ($required_fields as $field) {
            if (!isset($item[$field]) || empty($item[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
    }
    
    return $data;
}

function createBackup($file) {
    if (file_exists($file)) {
        $backup_name = 'experience_backup_' . date('Y-m-d_H-i-s') . '.json';
        $backup_path = dirname($file) . '/backups/' . $backup_name;
        copy($file, $backup_path);
        return $backup_name;
    }
    return null;
}

try {
    // Verificar password si está configurado
    $password = $_POST['password'] ?? $_REQUEST['password'] ?? '';
    if ($upload_password !== 'tu_password_seguro_aqui' && $password !== $upload_password) {
        throw new Exception('Invalid password');
    }
    
    $updated_data = null;
    
    // Manejar upload de archivo
    if (isset($_FILES['linkedin_export']) && $_FILES['linkedin_export']['error'] === UPLOAD_ERR_OK) {
        $upload_file = $_FILES['linkedin_export']['tmp_name'];
        $file_content = file_get_contents($upload_file);
        
        // Intentar parsear como JSON
        $raw_data = json_decode($file_content, true);
        if ($raw_data === null) {
            throw new Exception('Invalid JSON file');
        }
        
        $updated_data = processLinkedInData($raw_data);
        
    } elseif (isset($_POST['experience_data'])) {
        // Manejar datos JSON enviados directamente
        $raw_data = json_decode($_POST['experience_data'], true);
        if ($raw_data === null) {
            throw new Exception('Invalid JSON data');
        }
        
        $updated_data = processLinkedInData($raw_data);
        
    } else {
        throw new Exception('No data provided');
    }
    
    if (empty($updated_data)) {
        throw new Exception('No valid experience data found');
    }
    
    // Crear backup del archivo actual
    $backup_name = createBackup($experience_file);
    
    // Guardar los nuevos datos
    $json_data = json_encode($updated_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if (file_put_contents($experience_file, $json_data) === false) {
        throw new Exception('Failed to save experience data');
    }
    
    // Log the update
    error_log("Experience data updated successfully. Backup: " . ($backup_name ?? 'none'));
    
    echo json_encode([
        'success' => true,
        'message' => 'Experience data updated successfully',
        'backup_created' => $backup_name,
        'items_updated' => count($updated_data),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    error_log("Error updating experience data: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
