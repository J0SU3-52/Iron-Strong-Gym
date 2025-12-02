<?php
// Configurar la duración de la sesión a 15 horas
ini_set('session.gc_maxlifetime', 54000); // 15 horas
session_set_cookie_params(54000); // 15 horas

// Iniciar la sesión
session_start();
