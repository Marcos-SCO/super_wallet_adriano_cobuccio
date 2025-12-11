<footer class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-5 mt-12 border-t border-blue-700">
    <div class="container mx-auto px-4 flex flex-col items-center text-center space-y-2">
        
        <p class="text-xs text-gray-300">
            &copy; {{ date('Y') }} SuperWallet. {{ __('messages.all_rights_reserved') }}.
        </p>

        <p class="text-sm">
            {{ __('messages.developed_by') }}
            <a href="https://github.com/Marcos-SCO" target="_blank" rel="noopener noreferrer"
               class="text-yellow-300 hover:text-yellow-400 font-semibold transition-colors duration-200">
               Marcos-SCO
            </a>
        </p>

    </div>
</footer>
