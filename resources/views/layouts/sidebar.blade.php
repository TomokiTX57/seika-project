<!-- ハンバーガーメニュー（モバイル） -->
<!-- 
<div class="md:hidden p-4">
    <button id="menu-button" class="text-white bg-gray-800 px-4 py-2 rounded">
        ☰
    </button>
</div>
-->


<!-- モバイルメニュー -->
<!-- 
<div id="mobile-menu" class="fixed right-0 top-0 h-full w-64 bg-gray-800 text-white shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <ul class="mt-16 space-y-6 p-4">
        <li><a href="{{ route('players.index') }}" class="block hover:text-blue-400">Player検索</a></li>
        <li><a href="{{ route('zero-system.users') }}" class="block hover:text-blue-400">0円システム利用者表示</a></li>
        <li><a href="{{ route('tournaments.index') }}" class="block hover:text-blue-400">トーナメント</a></li>
        <li><a href="{{ route('players.subscribed') }}" class="block hover:text-blue-400">サブスク利用者表示</a></li>
    </ul>
</div> 
-->

<!-- PC用サイドバー -->
<!-- layouts/sidebar.blade.php -->
<aside class="bg-red-500 w-56 p-6 h-screen hidden md:flex flex-col">
    <nav class="flex-1">
        <ul class="space-y-4 text-white">
            <li><a href="{{ route('players.index') }}" class="block px-4 py-2 hover:bg-blue-700">Player検索</a></li>
            <li><a href="{{ route('zero-system.users') }}" class="block px-4 py-2 hover:bg-blue-700">0円システム利用者表示</a></li>
            <li><a href="{{ route('tournaments.index') }}" class="block px-4 py-2 hover:bg-blue-700">トーナメント</a></li>
            <li><a href="{{ route('players.subscribed') }}" class="block px-4 py-2 hover:bg-blue-700">サブスク利用者表示</a></li>
        </ul>
    </nav>
</aside>