<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Quetzalcóatl</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      @auth
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo (route('home'));?>">Inicio <span class="sr-only">(current)</span></a>
        </li>
      @endauth
      @guest
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo (route('welcome'));?>">Bienvenido <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ( url('/login') ); ?>">Ingresar</a>
        </li>
      @endguest
      <li class="nav-item">
        <a class="nav-link" href="<?php echo route('restaurant.branchoffices'); ?>">Sucursales</a>
      </li>
      @auth
        @if( \Auth::user()->role === 'adminRestaurant' )
            <li class="nav-item">
                <a class="nav-link" href="<?php echo route('saucer.form'); ?>">Agregar platillo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= route('restaurant.form') ?>">Agregar sucursal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo ( url('/register') ); ?>">Registrate</a>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Cerrar sesión') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
       @endauth
    </ul>
  </div>
</nav>
