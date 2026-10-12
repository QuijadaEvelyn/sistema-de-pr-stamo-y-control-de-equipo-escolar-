
# Stock Tecnológico

## 1. Prompt utilizado

Contexto: Una institución educativa necesita un sistema web para controlar laptops, proyectores, y otro equipos. El sitio tendrá una landing page pública con información, reglas de préstamo y contacto. Los usuarios autenticados trabajarán con dos roles: Solicitante y Encargado.

Funciones mínimas del sistema:
inventario: Consultar equipos, categorías, disponibilidad y estado.
Préstamos: solicitar equipo, aprobar o rechazar, registrar entrega y devolución.
Incidencias: reportar daño o falla y actualizar sus seguimiento.



Las entidades principales que manejamos son: usuarios(users), categorías(categories), equipo(equipment), prestamos(loans), artículos de préstamo(loan_items) y incidentes(incidents)


Se ocupara 
Roles y permisos:
Solicitante: Iniciar sesión, consultar disponibilidad, solicitar préstamos, revisar su estado y reportar incidencias.

Encargado: Administrar equipos y categorías, decidir solicitudes, registrar entregas y devoluciones, y atender incidencias 

stock tecnológico:

Vamos a trabajar con Laravel 13, utilizando arquitectura MVC, PHP 8.4 y base de datos en MariaDB con el puerto 3305.
Vamos a trabajar solamente con app.css, app.js y con plantillas Blade.


Solicitud:
Generame archivo por archivo y código completo este proyecto. Primero dime las carpetas y archivos que debo de crear y dame código completo. Dame primero las migraciones, después los modelos, luego los controladores, después el archivo de estilos (app.css), después el js, después las páginas o vistas en Blade y por último las rutas.

También necesito que me des un Seeder con un solicitante, encargado predeterminado y con 10 usuarios de prueba.

Utiliza esta paleta de colores, agregando soporte para modo claro y modo oscuro:
/* CSS HEX */
--rich-mahogany: #3f0d12ff;
--ruby-red: #a71d31ff;
--lemon-chiffon: #f1f0ccff;
--soft-fawn: #d5bf86ff;
--faded-copper: #8d775fff;

Necesito que tenga esta fuente tipográfica:

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">

Toma como referencia un diseño moderno y limpio: tablas con bordes redondeados, sin degradados, sin utilizar emojis, solo iconos SVG. Utilizar un sidebar lateral para el panel de administración y del paciente. Haz una interfaz profesional con pantallas tanto para la parte pública (Landing / Publicidad) como para los dashboards privados.

Si hay algún error en lógica toma las mejores decisiones para solucionarlo. Crear inicio de sesión sencillo, sin instalar librerías extras (usando Auth nativo de Laravel con controladores y middlewares de acceso por roles: 'solicitante' y 'encargado'). 
La base de datos debe estar correctamente normalizada y declarada estrictamente en las migraciones.

Te proporciono capturas de pantalla de la interfaz, puedes tomarla como referencia.

## 2. Maquetación del sistema

La siguiente imagen presenta la maquetación
de las diferentes interfaces del sistema
Stock Tecnológico.


![Maquetación completa del sistema](maquetacion_completa.png)