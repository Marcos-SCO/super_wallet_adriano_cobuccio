<nav class="bg-gradient-to-r from-blue-500 to-blue-700 shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            {{-- Brand/Logo --}}
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <a href="{{ route('wallet.index') }}" class="text-xl font-bold text-white hover:text-blue-100 transition">
                    SuperWallet
                </a>
            </div>

            {{-- Right Section --}}
            <div class="flex items-center space-x-4">
                @auth
                    <div class="flex items-center space-x-3 border-r border-blue-400 pr-4">
                        <span class="text-blue-100 text-sm font-medium truncate max-w-xs">
                            {{ __('messages.hello', ['name' => auth()->user()->name]) }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-blue-100 hover:text-white hover:bg-blue-500 px-3 py-1 rounded transition">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm text-blue-100 hover:text-white hover:bg-blue-500 px-3 py-1 rounded transition">
                        {{ __('messages.login') }}
                    </a>
                @endguest

                {{-- Language Switcher --}}
                <div class="border-l border-blue-400 pl-4 flex items-center space-x-2">
                    <a href="{{ route('locale.switch', 'en') }}"
                        class="text-xs font-semibold px-2 py-1 rounded transition {{ app()->getLocale() === 'en' ? 'bg-white text-blue-600' : 'text-blue-100 hover:text-white hover:bg-blue-500' }}">
                        EN
                    </a>
                    <span class="text-blue-300">/</span>
                    <a href="{{ route('locale.switch', 'pt') }}"
                        class="text-xs font-semibold px-2 py-1 rounded transition {{ app()->getLocale() === 'pt' ? 'bg-white text-blue-600' : 'text-blue-100 hover:text-white hover:bg-blue-500' }}">
                        PT
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
