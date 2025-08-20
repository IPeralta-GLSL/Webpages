# LinkedIn Experience Auto-Update System

Este sistema te permite actualizar automáticamente tu experiencia profesional desde tu perfil de LinkedIn.

## 🚀 Características

- ✅ Actualización semi-automática desde exports de LinkedIn
- ✅ Interfaz web para subir archivos
- ✅ Script de línea de comandos
- ✅ Backups automáticos antes de cada actualización
- ✅ Validación de datos
- ✅ Soporte para múltiples formatos (JSON, CSV)

## 📋 Métodos de Actualización

### Método 1: Interfaz Web (Recomendado)

1. **Visita**: `https://tu-dominio.com/admin_experience.html`
2. **Sigue las instrucciones** para exportar datos desde LinkedIn
3. **Sube el archivo** con tu password
4. **¡Listo!** Tu experiencia se actualiza automáticamente

### Método 2: Línea de Comandos

```bash
# Subir archivo de export
./update_linkedin.sh -f linkedin_export.json -p tu_password

# O simplemente
./update_linkedin.sh archivo.json password
```

### Método 3: API Direct

```bash
# Con curl
curl -X POST "https://tu-dominio.com/update_experience.php" \
     -F "linkedin_export=@archivo.json" \
     -F "password=tu_password"
```

## 📥 Cómo Exportar desde LinkedIn

### Paso a paso:

1. **Ve a LinkedIn** → Configuración y privacidad → Privacidad de datos
2. **Selecciona** "Obtener una copia de tus datos"
3. **Elige** "¿Quieres algo en particular? Selecciona los archivos de datos que quieres"
4. **Marca** "Posiciones" o "Perfil"
5. **Click** "Solicitar archivo"
6. **Espera el email** (normalmente 10-15 minutos)
7. **Descarga y extrae** el ZIP
8. **Sube** el archivo `Positions.csv` o `Profile.json`

### Formatos soportados:

- ✅ `Positions.csv` (export estándar de LinkedIn)
- ✅ `Profile.json` (export completo)
- ✅ Cualquier JSON en el formato del sistema
- ✅ Datos JSON personalizados

## 🔧 Configuración

### Cambiar Password

Edita `update_experience.php` línea 12:
```php
$upload_password = 'tu_password_seguro_aqui'; // Cambia esto
```

### Cambiar URL del servidor

Edita `update_linkedin.sh` línea 6:
```bash
SERVER_URL="https://tu-dominio.com/update_experience.php"
```

## 📁 Estructura de Archivos

```
/
├── experience.json              # Datos actuales de experiencia
├── update_experience.php        # API para actualizar datos
├── admin_experience.html        # Interfaz web de administración
├── update_linkedin.sh          # Script de línea de comandos
├── get_linkedin_experience.php  # API para obtener datos
├── backups/                     # Backups automáticos
│   ├── experience_backup_2025-08-19_22-30-15.json
│   └── experience_backup_2025-08-19_21-15-42.json
└── README_linkedin_update.md    # Este archivo
```

## 🛡️ Seguridad

- ✅ **Password protegido**: Requiere contraseña para actualizaciones
- ✅ **Backups automáticos**: Siempre se crea backup antes de actualizar
- ✅ **Validación de datos**: Verifica formato antes de guardar
- ✅ **Logs de errores**: Registra todas las operaciones
- ✅ **CORS configurado**: Solo permite requests autorizados

## 🎯 Formato de Datos

### Formato del sistema:
```json
[
    {
        "id": 1,
        "position": "Senior Developer",
        "company": "Empresa XYZ",
        "location": "Remote",
        "startDate": "2023",
        "endDate": "Present",
        "duration": "2023 - Present",
        "description": "Descripción del trabajo...",
        "achievements": [
            "Logro 1",
            "Logro 2"
        ],
        "technologies": ["JavaScript", "React", "Node.js"]
    }
]
```

### El sistema convierte automáticamente desde:
- Exports de LinkedIn (CSV/JSON)
- Formatos personalizados
- Datos existentes

## 🚨 Troubleshooting

### Error: "Invalid password"
- Verifica que el password en `update_experience.php` coincida

### Error: "Invalid JSON file"
- Asegúrate de que el archivo no esté corrupto
- Prueba con el validador JSON en la interfaz web

### Error: "Failed to save experience data"
- Verifica permisos de escritura en el directorio
- Revisa el log de errores del servidor

### No se ven los cambios
- Refresca la página (Ctrl+F5)
- Verifica que el archivo se haya actualizado
- Revisa la consola del navegador

## 📞 Soporte

Si tienes problemas:

1. **Revisa los logs** del servidor
2. **Usa la interfaz web** para validar datos
3. **Verifica los backups** en `/backups/`
4. **Consulta este README** para troubleshooting

## 🔄 Workflow Recomendado

1. **Actualiza LinkedIn** con tu nueva experiencia
2. **Exporta los datos** siguiendo las instrucciones
3. **Sube via interfaz web** (más fácil) o línea de comandos
4. **Verifica los cambios** en tu portfolio
5. **Los backups se crean automáticamente** por seguridad

---

**¡Tu experiencia profesional ahora se mantiene siempre actualizada! 🎉**
