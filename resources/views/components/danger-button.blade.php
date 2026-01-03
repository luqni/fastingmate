<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-red-50 text-red-600 border border-transparent rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-red-100 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-500/30 transition-all duration-200']) }}>
    {{ $slot }}
</button>
