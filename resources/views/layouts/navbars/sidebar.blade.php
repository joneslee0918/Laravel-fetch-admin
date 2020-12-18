<div class="sidebar" data-color="purple" data-background-color="black">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
    <div class="logo">
        <a href="/dashboard" class="simple-text logo-normal" style="text-transform: none;">
            {{ __('Fetch | PET') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="/dashboard">
                    <i class="material-icons">dashboard</i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'user' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <i class="material-icons">account_circle</i>
                    <p>{{ __('Users Management') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'category' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('category.index') }}">
                    <i class="material-icons">subtitles</i>
                    <p>{{ __('Category Management') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'breed' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('breed.index') }}">
                    <i class="material-icons">subtitles</i>
                    <p>{{ __('Breed Management') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'ads' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('ads.index') }}">
                    <i class="material-icons">pets</i>
                    <p>{{ __('Ads Management') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'chat' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('chat.index') }}">
                    <i class="material-icons">forumbee</i>
                    <p>{{ __('Chat Management') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>