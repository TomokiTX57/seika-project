<!-- resources/views/components/smart-menu.blade.php -->
<x-responsive-nav-link :href="route('players.index')" :active="request()->routeIs('players.*')">
    プレイヤー管理
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('tournaments.index')" :active="request()->routeIs('tournaments.*')">
    トーナメント管理
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('zero-system.users')" :active="request()->routeIs('zero-system.*')">
    0円システム
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('players.subscribed')" :active="request()->routeIs('players.subscribed')">
    サブスク利用者表示
</x-responsive-nav-link>