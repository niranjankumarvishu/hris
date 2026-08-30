<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <flux:sidebar
        sticky
        collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
    >

        {{-- =========================================================
             SIDEBAR HEADER
        ========================================================== --}}

        <flux:sidebar.header>

            <x-app-logo
                :sidebar="true"
                href="/dashboard"
            />

            <flux:sidebar.collapse class="lg:hidden" />

        </flux:sidebar.header>


        {{-- =========================================================
             TEAM SWITCHER
        ========================================================== --}}

        <livewire:team-switcher />


        {{-- =========================================================
             PLATFORM NAVIGATION
        ========================================================== --}}

        <flux:sidebar.nav>

            <flux:sidebar.group
                :heading="__('Platform')"
                class="grid"
            >

                {{-- Dashboard --}}
                <flux:sidebar.item
                    icon="home"
                    href="/dashboard"
                    :current="request()->is('dashboard')"
                >
                    {{ __('Dashboard') }}
                </flux:sidebar.item>


                {{-- Employees --}}
                <flux:sidebar.item
                    icon="users"
                    href="/employees"
                    :current="request()->is('employees')"
                >
                    {{ __('Employees') }}
                </flux:sidebar.item>


                {{-- Leaves --}}
                <flux:sidebar.item
                    icon="calendar"
                    href="/leaves"
                    :current="request()->is('leaves')"
                >
                    {{ __('Leaves') }}
                </flux:sidebar.item>


                {{-- Attendance --}}
                <flux:sidebar.item
                    icon="clipboard-document-check"
                    href="/attendance"
                    :current="request()->is('attendance')"
                >
                    {{ __('Attendance') }}
                </flux:sidebar.item>


                {{-- Leave Statistics --}}
                <flux:sidebar.item
                    icon="chart-bar"
                    href="/leave-statistics"
                    :current="request()->is('leave-statistics')"
                >
                    {{ __('Leave Statistics') }}
                </flux:sidebar.item>

            </flux:sidebar.group>

        </flux:sidebar.nav>


        {{-- =========================================================
             SPACER
        ========================================================== --}}

        <flux:spacer />


        {{-- =========================================================
             BOTTOM NAVIGATION
        ========================================================== --}}

        <flux:sidebar.nav>

            <flux:sidebar.item
                icon="folder-git-2"
                href="https://github.com/laravel/livewire-starter-kit"
                target="_blank"
            >
                {{ __('Repository') }}
            </flux:sidebar.item>


            <flux:sidebar.item
                icon="book-open-text"
                href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank"
            >
                {{ __('Documentation') }}
            </flux:sidebar.item>

        </flux:sidebar.nav>


        {{-- =========================================================
             DESKTOP USER MENU
        ========================================================== --}}

        <x-desktop-user-menu
            class="hidden lg:block"
            :name="auth()->user()->name"
        />

    </flux:sidebar>


    {{-- =============================================================
         MOBILE HEADER
    ============================================================= --}}

    <flux:header class="lg:hidden">

        <flux:sidebar.toggle
            class="lg:hidden"
            icon="bars-2"
            inset="left"
        />

        <flux:spacer />


        <flux:dropdown
            position="top"
            align="end"
        >

            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />


            <flux:menu>

                <flux:menu.radio.group>

                    <div class="p-0 text-sm font-normal">

                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">

                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />

                            <div class="grid flex-1 text-start text-sm leading-tight">

                                <flux:heading class="truncate">
                                    {{ auth()->user()->name }}
                                </flux:heading>

                                <flux:text class="truncate">
                                    {{ auth()->user()->email }}
                                </flux:text>

                            </div>

                        </div>

                    </div>

                </flux:menu.radio.group>


                <flux:menu.separator />


                <flux:menu.radio.group>

                    <flux:menu.item
                        :href="route('profile.edit')"
                        icon="cog"
                    >
                        {{ __('Settings') }}
                    </flux:menu.item>

                </flux:menu.radio.group>


                <flux:menu.separator />


                {{-- Logout --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="w-full"
                >

                    @csrf

                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        {{ __('Log out') }}
                    </flux:menu.item>

                </form>

            </flux:menu>

        </flux:dropdown>

    </flux:header>


    {{-- =============================================================
         PAGE CONTENT
    ============================================================= --}}

    {{ $slot }}


    {{-- =============================================================
         CREATE TEAM MODAL
    ============================================================= --}}

    <livewire:create-team-modal />


    {{-- =============================================================
         TOAST
    ============================================================= --}}

    @persist('toast')

        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>

    @endpersist


    {{-- =============================================================
         FLUX SCRIPTS
    ============================================================= --}}

    @fluxScripts

</body>

</html>