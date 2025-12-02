<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Asistencia</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-image: url('../img/logo-desenfocado.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .container {
      background: #fff;
      padding: 45px;
      border-radius: 10px;
      box-shadow: 0 0 30px #FDFEFE;
      text-align: center;
    }

    h2 {
      font-family: Tahoma, sans-serif;
      font-weight: bold;
      font-size: 45px;
      margin-bottom: 20px;
      padding: 40px;
    }

    .form-control {
      font-size: 28px;
      padding: 10px;
      margin-right: 10px;
    }

    .btn-custom {
      font-size: 24px;
      padding: 10px 20px;
      background-color: #28B463;
      color: white;
      border: none;
    }

    .btn-custom:hover {
      background-color: #45a049;
    }

    .fullname {
      color: #310273;
    }

    .active {
      color: green;
      font-size: 38px;
      font-weight: bold;
      padding: 45px;
    }

    .expired {
      color: red;
      font-size: 38px;
      font-weight: bold;
    }

    .error {
      color: red;
      font-size: 38px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>REGISTRO DE ASISTENCIA <i class="fa-solid fa-user-check fa-lg" style="color: #020812;"></i></h2>
    <form id="attendanceForm" class="form-inline justify-content-center" onsubmit="event.preventDefault(); checkAttendance();">
      <div class="form-group mb-2">
        <label for="pin" class="sr-only">Ingrese su PIN</label>
        <input type="text" id="pin" name="pin" class="form-control" placeholder="Ingrese su PIN" maxlength="4" required>
      </div>
      <button type="submit" class="btn btn-custom mb-2">Registrar</button>
    </form>
    <p id="message"></p>
  </div>
  03
  <script>
    function checkAttendance() {
      const pin = document.getElementById('pin').value;
      const message = document.getElementById('message');

      fetch('actions/check_attendance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            pin: pin
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'activo') {
            message.innerHTML = `¡BIENVENIDO! <span class="fullname">${data.fullname}</span>, MENSUALIDAD VIGENTE <i class="fa-regular fa-face-laugh-wink"></i>`;
            message.className = 'active';
          } else if (data.status === 'vencido') {
            message.innerHTML = `¡ACCESO DENEGADO <span class="fullname">${data.fullname}</span>! ¡TU MENSUALIDAD HA VENCIDO! <i class="fa-regular fa-face-sad-cry fa-lg"></i>`;
            message.className = 'expired';
          } else if (data.status === 'no_valido') {
            message.innerHTML = '¡PIN INCORRECTO! <i class="fa-regular fa-heart-crack fa-xl"></i>';
            message.className = 'error';
          }

          setTimeout(() => {
            message.textContent = '';
            message.className = '';
            document.getElementById('pin').value = '';
            document.getElementById('pin').focus();
          }, 4000);
        })
        .catch(error => {
          console.error('Error:', error);
          if (error.message.includes('Failed to fetch')) {
            message.textContent = 'No se pudo conectar con el servidor. Por favor, verifica tu conexión a Internet.';
          } else {
            message.textContent = 'Ocurrió un error al procesar tu solicitud.';
          }
          message.className = 'error';

          setTimeout(() => {
            message.textContent = '';
            message.className = '';
            document.getElementById('pin').value = '';
            document.getElementById('pin').focus();
          }, 5000);
        });
    }

    document.getElementById('pin').addEventListener('keydown', function(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        checkAttendance();
      }
    });

    window.onload = function() {
      document.getElementById('pin').focus();
    };

    // Mantener la sesión activa enviando una solicitud cada 29 minutos
    setInterval(() => {
      fetch('actions/keep_alive.php', {
        method: 'GET'
      }).then(response => console.log('Manteniendo la sesión activa.'));
    }, 54000000); // 15 hours (15 * 60 * 1000)
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>