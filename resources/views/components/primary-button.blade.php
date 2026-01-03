<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-primary-300 border border-transparent rounded-2xl font-bold text-xs text-gray-900 uppercase tracking-widest hover:bg-primary-400 active:scale-95 focus:outline-none focus:ring-4 focus:ring-primary-500/30 transition-all duration-200 shadow-lg shadow-primary-500/30']) }}>
    {{ $slot }}
</button>
