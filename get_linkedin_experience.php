<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar peticiones OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Función para extraer información de LinkedIn
function getLinkedInProfile($profileUrl) {
    // Por ahora, devolvemos los datos estáticos del JSON
    // En una implementación real, aquí harías web scraping o usarías la API de LinkedIn
    
    $experienceFile = __DIR__ . '/experience.json';
    
    if (file_exists($experienceFile)) {
        $experienceData = json_decode(file_get_contents($experienceFile), true);
        return [
            'success' => true,
            'data' => $experienceData,
            'source' => 'local_file',
            'last_updated' => filemtime($experienceFile),
            'update_info' => [
                'admin_panel' => 'admin_experience.html',
                'api_endpoint' => 'update_experience.php',
                'cli_script' => 'update_linkedin.sh',
                'backup_dir' => 'backups/'
            ]
        ];
    } else {
        return [
            'success' => false,
            'error' => 'Experience data file not found',
            'source' => 'none'
        ];
    }
}

// Función para scraping real de LinkedIn (experimental)
function scrapeLinkedInProfile($profileUrl) {
    // ADVERTENCIA: Este código es solo para uso personal y educativo
    // LinkedIn tiene términos de servicio que prohíben el scraping automatizado
    // Usa esto solo para tu propio perfil y considera usar la API oficial
    
    try {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.5',
                    'Accept-Encoding: gzip, deflate',
                    'Connection: keep-alive',
                    'Upgrade-Insecure-Requests: 1'
                ],
                'timeout' => 30
            ]
        ]);
        
        $html = file_get_contents($profileUrl, false, $context);
        
        if ($html === false) {
            throw new Exception('Failed to fetch LinkedIn profile');
        }
        
        // Crear un DOMDocument para parsear el HTML
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        
        $xpath = new DOMXPath($dom);
        
        // Intentar extraer información básica del perfil
        $experience = [];
        
        // Buscar elementos de experiencia (esto puede cambiar según la estructura de LinkedIn)
        $experienceNodes = $xpath->query('//section[contains(@class, "experience")]//li[contains(@class, "experience-item")]');
        
        foreach ($experienceNodes as $node) {
            $titleNode = $xpath->query('.//h3[contains(@class, "t-16")]', $node)->item(0);
            $companyNode = $xpath->query('.//h4[contains(@class, "t-14")]', $node)->item(0);
            $dateNode = $xpath->query('.//*[contains(@class, "date-range")]', $node)->item(0);
            
            if ($titleNode && $companyNode) {
                $experience[] = [
                    'position' => trim($titleNode->textContent),
                    'company' => trim($companyNode->textContent),
                    'duration' => $dateNode ? trim($dateNode->textContent) : 'N/A',
                    'description' => '',
                    'technologies' => []
                ];
            }
        }
        
        return [
            'success' => true,
            'data' => $experience,
            'source' => 'scraped',
            'last_updated' => time(),
            'note' => 'Scraped data may be incomplete due to LinkedIn\'s dynamic loading'
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'source' => 'scraping_error'
        ];
    }
}

// Endpoint principal
try {
    $profileUrl = isset($_GET['profile']) ? $_GET['profile'] : 'https://www.linkedin.com/in/ignacio-peralta-768396174/';
    $method = isset($_GET['method']) ? $_GET['method'] : 'file';
    
    if ($method === 'scrape') {
        // Usar scraping (experimental)
        $result = scrapeLinkedInProfile($profileUrl);
    } else {
        // Usar archivo local
        $result = getLinkedInProfile($profileUrl);
    }
    
    echo json_encode($result, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
