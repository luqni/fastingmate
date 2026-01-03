<nav x-data="{ open: false }" class="bg-white/80 dark:bg-light/80 backdrop-blur-md border-b border-secondary dark:border-secondary sticky top-0 z-40 transition-colors duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-5 lg:px-5">
        <div class="d-flex justify-content-between h-16">
            <div class="d-flex">
                <!-- Logo -->
                <div class="shrink-0 d-flex align-items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="d-block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="d-none space-x-8 sm:-my-px sm:ms-10 sm:d-flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('fasting-debts.index')" :active="request()->routeIs('fasting-debts.*')">
                        {{ __('Fasting Debts') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->gender === 'female')
                    <x-nav-link :href="route('menstrual-cycles.index')" :active="request()->routeIs('menstrual-cycles.*')">
                        {{ __('Menstrual Cycles') }}
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="route('fidyah.index')" :active="request()->routeIs('fidyah.*')">
                        {{ __('Zakat & Fidyah') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="d-none sm:d-flex sm:align-items-center sm:ms-6 gap-2">
                <x-privacy-toggle />

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="d-inline-d-flex align-items-center px-3 py-2 border border-transparent small leading-4 fw-medium rounded text-secondary dark:text-muted bg-white dark:bg-light hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 d-flex align-items-center sm:d-none">
                <button @click="open = ! open" class="d-inline-d-flex align-items-center justify-content-center p-2 rounded text-muted dark:text-secondary hover:text-secondary dark:hover:text-muted hover:bg-light dark:hover:bg-light focus:outline-none focus:bg-light dark:focus:bg-light focus:text-secondary dark:focus:text-muted transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'d-none': open, 'd-inline-d-flex': ! open }" class="d-inline-d-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'d-none': ! open, 'd-inline-d-flex': open }" class="d-none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'d-block': open, 'd-none': ! open}" class="d-none sm:d-none">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fasting-debts.index')" :active="request()->routeIs('fasting-debts.*')">
                {{ __('Fasting Debts') }}
            </x-responsive-nav-link>

            @if(Auth::user()->gender === 'female')
            <x-responsive-nav-link :href="route('menstrual-cycles.index')" :active="request()->routeIs('menstrual-cycles.*')">
                {{ __('Menstrual Cycles') }}
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('fidyah.index')" :active="request()->routeIs('fidyah.*')">
                {{ __('Zakat & Fidyah') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-secondary dark:border-secondary">
            <div class="px-4">
                <div class="fw-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="fw-medium small text-secondary">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
