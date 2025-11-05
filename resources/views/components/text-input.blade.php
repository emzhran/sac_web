@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white text-black border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-md shadow-sm']) }}>
