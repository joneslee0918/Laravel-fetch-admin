<div class="sidebar" data-color="purple" data-background-color="black" >
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="/home" class="simple-text logo-normal" style="text-transform: none;">
      {{ __('Intraclub | SCNP') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home.index') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'slider' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('slider.index') }}">
          <i class="material-icons">perm_media</i>
            <p>{{ __('Slider Image Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'user' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('user.index') }}">
          <i class="material-icons">account_circle</i>
            <p>{{ __('User Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'role' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('role.index') }}">
          <i class="material-icons">account_circle</i>
            <p>{{ __('Rights Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'transaction' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('transaction.index') }}">
          <i class="material-icons">subtitles</i>
            <p>{{ __('Transaction Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'groups' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('group.index') }}">
          <i class="material-icons">group</i>
            <p>{{ __('Group Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'news' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('news.index') }}">
          <i class="material-icons">chat</i>
            <p>{{ __('News Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'email' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('email.index') }}">
          <i class="material-icons">local_post_office</i>
            <p>{{ __('Email Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'championship' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('championship.index') }}">
          <i class="material-icons">grade</i>
            <p>{{ __('Championship Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'matches' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('matches.index') }}">
          <i class="material-icons">sports_volleyball</i>
            <p>{{ __('Matches Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'chat' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('chat/0/0') }}">
          <i class="material-icons">forumbee</i>
            <p>{{ __('chat Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'comments' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('comments.index') }}">
          <i class="material-icons">more_horiz</i>
            <p>{{ __('Comments Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'report' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('report.index') }}">
          <i class="material-icons">report_problem</i>
            <p>{{ __('Report Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'conditions' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('conditions.index') }}">
          <i class="material-icons">article</i>
            <p>{{ __('Conditions Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'exercise' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('exercise.index') }}">
          <i class="material-icons">shuffle</i>
            <p>{{ __('Tactics Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'coachrole' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('coachrole.index') }}">
          <i class="material-icons">how_to_reg</i>
            <p>{{ __('Call Players') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'notification' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('notification.index') }}">
          <i class="material-icons">access_alarm</i>
            <p>{{ __('Notification History') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'services_setting' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('services-setting.index') }}">
          <i class="material-icons">access_alarm</i>
            <p>{{ __('Services Management') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'services_transaction' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('services-transaction.index') }}">
          <i class="material-icons">subtitles</i>
            <p>{{ __('Service Transaction') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'services' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('services.index') }}">
          <i class="material-icons">access_alarm</i>
            <p>{{ __('Services') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'service_history' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('services-history.index') }}">
          <i class="material-icons">history_toggle_off</i>
            <p>{{ __('Service History') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'booking' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('booking.index') }}">
          <i class="material-icons">history</i>
            <p>{{ __('Booking History') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'shop' ? ' active' : '' }}">
        <a class="nav-link"href="{{ route('product.index') }}">
          <i class="material-icons">shop</i>
            <p>{{ __('Scnp Shop') }}</p>
        </a>
      </li>
    </ul>
  </div>
</div>