<header class="ft-topbar">
    <span style="color:#98a2b3;font-size:18px">⌁</span>
    <x-dropdown align="right" width="48">
        <x-slot name="trigger"><button class="ft-topbar-user" type="button"><span>{{ Auth::user()->name ?? 'Account' }}</span><span style="font-size:10px">⌄</span></button></x-slot>
        <x-slot name="content"><x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link><form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link></form></x-slot>
    </x-dropdown>
</header>
