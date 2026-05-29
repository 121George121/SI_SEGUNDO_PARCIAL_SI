<?php
// export_db.php

$host = '127.0.0.1';
$db   = 'incripcion_CUP';
$user = 'postgres';
$pass = 'Jorge2005'; // reemplaza con la contraseña real

$backupFile = __DIR__ . '/backup_cup.sql';
$pgDumpPath = '"C:\\Program Files\\PostgreSQL\\17\\bin\\pg_dump.exe"'; // ruta correcta a pg_dump.exe

$command = "$pgDumpPath -h $host -U $user -F p $db > \"$backupFile\"";

// Ejecutar comando
system($command, $returnVar);

if ($returnVar === 0) {
    echo "Backup generado correctamente en: $backupFile\n";
} else {
    echo "Error al generar el backup. Código: $returnVar\n";
}