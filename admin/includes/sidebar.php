<div id="sidebar">
  <ul>
    <li class="<?php if ($page == 'dashboard') {
                  echo 'active';
                } ?>"><a href="index.php"><i class="fa-solid fa-house fa-xl"></i> <span>Home</span></a> </li>
    <li class="submenu"> <a href=""><i class="fa-solid fa-users fa-xl"></i> <span>Gestionar Clientes</span> <span class="label label-important"><?php include 'dashboard-usercount.php'; ?> </span></a>
      <ul>
        <li class="<?php if ($page == 'clients') {
                      echo 'active';
                    } ?>"><a href="clients.php"><i class="fa-solid fa-users"></i> Clientes Registrados </a></li>
        <li class="<?php if ($page == 'clients-entry') {
                      echo 'active';
                    } ?>"><a href="clients-entry.php"><i class="fa-solid fa-user-plus"></i> Registro de clientes </a></li>
        <li class="<?php if ($page == 'remove-clients') {
                      echo 'active';
                    } ?>"><a href="remove-clients.php"><i class="fa-solid fa-user-xmark"></i> Eliminar clientes </a></li>
        <li class="<?php if ($page == 'clients-update') {
                      echo 'active';
                    } ?>"><a href="clients-update.php"><i class="fa-solid fa-user-check"></i> Actualizar Clientes </a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="fa-solid fa-dumbbell fa-xl"></i> <span>Equipo de Gimnasio</span> <span class="label label-important"><?php include 'dashboard-equipcount.php'; ?> </span></a>
      <ul>
        <li class="<?php if ($page == 'list-equip') {
                      echo 'active';
                    } ?>"><a href="equipment.php"><i class="fas fa-arrow-right"></i> Listar equipos de gimnasio</a></li>
        <li class="<?php if ($page == 'add-equip') {
                      echo 'active';
                    } ?>"><a href="equipment-entry.php"><i class="fas fa-arrow-right"></i> Agregar equipo</a></li>
        <li class="<?php if ($page == 'remove-equip') {
                      echo 'active';
                    } ?>"><a href="remove-equipment.php"><i class="fas fa-arrow-right"></i> Quitar Equipo</a></li>
        <li class="<?php if ($page == 'update-equip') {
                      echo 'active';
                    } ?>"><a href="edit-equipment.php"><i class="fas fa-arrow-right"></i> Actualizar Detalles del Equipo</a></li>
      </ul>
    </li>

    <li class="<?php if ($page == 'attendance') {
                  echo 'submenu active';
                } else {
                  echo 'submenu';
                } ?>"> <a href="#"><i class="fa-solid fa-calendar-days fa-xl"></i> <span>Asistencia</span> <span class="label label-important"></a>
      <ul>
        <li class="<?php if ($page == 'view-attendance') {
                      echo 'active';
                    } ?>"><a href="view-attendance.php"><i class="fas fa-arrow-right"></i> Lista de Asistencia</a></li>
        <li class="<?php if ($page == 'attendance') {
                      echo 'active';
                    } ?>"><a href="attendance.php"><i class="fas fa-arrow-right"></i> Registro de Entrada</a></li>

      </ul>
    </li>

    <li class="<?php if ($page == 'views') {
                  echo 'submenu active';
                } else {
                  echo 'submenu';
                } ?>"> <a href="#"><i class="fa-solid fa-street-view fa-xl"></i><span>Visitas</span></a>
      <ul>
        <li class="<?php if ($page == 'views') {
                      echo 'active';
                    } ?>"><a href="views.php"><i class="fas fa-arrow-right"></i> Pagos Visitas</a></li>
        <!-- <li class="<?php if ($page == 'views') {
                          echo 'active';
                        } ?>"><a href="attendance.php"><i class="fas fa-arrow-right"></i> Registro de Entrada</a></li> -->

      </ul>
    </li>



    <li class="<?php if ($page == 'member-status') {
                  echo 'active';
                } ?>"><a href="member-status.php"><i class="fas fa-eye"></i> <span>Estado del Cliente </span></a></li>
    <li class="<?php if ($page == 'payment') {
                  echo 'active';
                } ?>"><a href="payment.php"><i class="fas fa-hand-holding-usd"></i> <span>Pagos</span></a></li>

    <!--<li class="<?php if ($page == 'announcement') {
                      echo 'active';
                    } ?>"><a href="announcement.php"><i class="fas fa-bullhorn"></i> <span>Notas</span></a></li> -->


    <li class="submenu"> <a href="#"><i class="fas fa-dumbbell"></i> <span>Rutinas</span> <span class="label label-important"><?php include 'dashboard-useroutine.php'; ?> </span></a>
      <ul>
        <li class="<?php if ($page == 'members') {
                      echo 'active';
                    } ?>"><a href="routine.php"><i class="fas fa-arrow-right"></i> Rutinas Personalizadas </a></li>
        <li class="<?php if ($page == 'members-entry') {
                      echo 'active';
                    } ?>"><a href="create-routines.php"><i class="fas fa-arrow-right"></i> Crear Rutina Personalizada </a></li>
        <li class="<?php if ($page == 'members-remove') {
                      echo 'active';
                    } ?>"><a href="eliminate-routine.php"><i class="fas fa-arrow-right"></i> Eliminar rutinas</a></li>
        <li class="<?php if ($page == 'members-update') {
                      echo 'active';
                    } ?>"><a href="update-routine.php"><i class="fas fa-arrow-right"></i> Actualizar rutinas </a></li>
      </ul>
    </li>



  </ul>
</div>