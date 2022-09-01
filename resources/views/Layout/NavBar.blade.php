<nav class="navbar navbar-expand-lg nav fixed-top mb-5" style="background-color: #005720;">
  <div class="container-fluid" >
    <a class="navbar-brand" href="#">
        <img src="{{url('/')}}/img/Logo.png" width="70rem" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars text-light"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-light" aria-current="page" href="{{route('create')}}">Crear Cliente</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled">Disabled</a> -->
        </li>
      </ul>
      <form class="d-flex" role="search">
        <a href="{{route('logout')}}"> <strong class="btn text-light">Cerrar session <i class="fas fa-sign-out-alt"></i> </strong></a>
      </form>
    </div>
  </div>
</nav>