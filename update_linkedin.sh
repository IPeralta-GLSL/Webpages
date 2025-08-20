#!/bin/bash

# Script para actualizar datos de experiencia desde LinkedIn
# Uso: ./update_linkedin.sh [archivo_export.json] [password]

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SERVER_URL="https://www.ignacioperalta.com/update_experience.php"  # Cambia por tu dominio
DEFAULT_PASSWORD="tu_password_seguro_aqui"  # Cambia por tu password

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== LinkedIn Experience Updater ===${NC}"
echo ""

# Función para mostrar ayuda
show_help() {
    echo "Uso: $0 [opciones]"
    echo ""
    echo "Opciones:"
    echo "  -f, --file FILE     Archivo de export de LinkedIn (JSON/CSV)"
    echo "  -p, --password PASS Password para autenticación"
    echo "  -u, --url URL       URL del servidor (default: $SERVER_URL)"
    echo "  -h, --help          Mostrar esta ayuda"
    echo ""
    echo "Ejemplos:"
    echo "  $0 -f linkedin_export.json -p mipassword"
    echo "  $0 --file Positions.csv"
    echo ""
}

# Parsear argumentos
EXPORT_FILE=""
PASSWORD=""
CUSTOM_URL=""

while [[ $# -gt 0 ]]; do
    case $1 in
        -f|--file)
            EXPORT_FILE="$2"
            shift 2
            ;;
        -p|--password)
            PASSWORD="$2"
            shift 2
            ;;
        -u|--url)
            CUSTOM_URL="$2"
            shift 2
            ;;
        -h|--help)
            show_help
            exit 0
            ;;
        *)
            if [[ -z "$EXPORT_FILE" ]]; then
                EXPORT_FILE="$1"
            elif [[ -z "$PASSWORD" ]]; then
                PASSWORD="$1"
            fi
            shift
            ;;
    esac
done

# Usar URL personalizada si se proporciona
if [[ -n "$CUSTOM_URL" ]]; then
    SERVER_URL="$CUSTOM_URL"
fi

# Verificar que existe curl
if ! command -v curl &> /dev/null; then
    echo -e "${RED}Error: curl no está instalado${NC}"
    exit 1
fi

# Si no se proporciona archivo, mostrar ayuda
if [[ -z "$EXPORT_FILE" ]]; then
    echo -e "${YELLOW}No se especificó archivo de export${NC}"
    echo ""
    show_help
    exit 1
fi

# Verificar que el archivo existe
if [[ ! -f "$EXPORT_FILE" ]]; then
    echo -e "${RED}Error: El archivo '$EXPORT_FILE' no existe${NC}"
    exit 1
fi

# Solicitar password si no se proporcionó
if [[ -z "$PASSWORD" ]]; then
    echo -n "Password: "
    read -s PASSWORD
    echo ""
fi

echo -e "${YELLOW}Subiendo archivo: $EXPORT_FILE${NC}"
echo -e "${YELLOW}Servidor: $SERVER_URL${NC}"
echo ""

# Realizar upload
response=$(curl -s -w "HTTPSTATUS:%{http_code}" \
    -X POST \
    -F "linkedin_export=@$EXPORT_FILE" \
    -F "password=$PASSWORD" \
    "$SERVER_URL")

# Separar código HTTP del body
http_code=$(echo "$response" | grep -o "HTTPSTATUS:[0-9]*" | cut -d: -f2)
body=$(echo "$response" | sed -E 's/HTTPSTATUS:[0-9]*$//')

# Procesar respuesta
if [[ "$http_code" == "200" ]]; then
    echo -e "${GREEN}✓ Upload exitoso${NC}"
    echo ""
    
    # Intentar parsear JSON response
    if command -v jq &> /dev/null; then
        echo "$body" | jq .
    else
        echo "$body"
    fi
    
    echo ""
    echo -e "${GREEN}¡Datos de experiencia actualizados correctamente!${NC}"
    echo -e "${BLUE}Visita tu portfolio para ver los cambios${NC}"
    
else
    echo -e "${RED}✗ Error en el upload (HTTP $http_code)${NC}"
    echo ""
    echo "$body"
fi

# Mostrar instrucciones adicionales
echo ""
echo -e "${BLUE}=== Próximos pasos ===${NC}"
echo "1. Visita tu portfolio para verificar los cambios"
echo "2. Los datos anteriores fueron respaldados automáticamente"
echo "3. Si algo salió mal, contacta al administrador del sistema"
