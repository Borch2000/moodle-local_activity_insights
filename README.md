# Activity Insights (local plugin)

**Autor:** Borja Jaudenes

Plugin local para Moodle que proporciona:
- Cálculo periódico de puntuaciones de riesgo por estudiante.
- Dashboard para profesorado con lista de estudiantes y factores que influyen.
- Webservice público para consumir puntuaciones.
- Tarea programada (scheduled task) para recalcular diariamente.
- Configuración administrativa para ajustar pesos y umbrales.

Instalación:
1. Copia la carpeta `activity_insights_full` (rename to `activity_insights`) dentro de `yourmoodle/local/`.
2. Accede a Administración -> Notifications para instalar las tablas y tareas.
3. Ajusta los parámetros en Site administration -> Plugins -> Local plugins -> Activity Insights.

Demostración:
- Visita `yourmoodle/local/activity_insights/index.php` (requiere permisos adecuados).

Notas:
- Este plugin está preparado para demostrar conocimientos avanzados de Moodle en entrevistas.
- Personaliza los mensajes y ajusta los pesos en settings.php antes de usar en producción.
