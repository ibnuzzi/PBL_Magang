<x-filament-panels::page>
    <div class="flex flex-col h-[600px] bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden" id="chat-container">
        <!-- Chat Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xl font-bold">
                🤖
            </div>
            <div>
                <h3 class="font-bold text-base leading-tight">Asisten Magang JTI</h3>
                <span class="text-xs text-indigo-100 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                    Online &amp; Siap Membantu
                </span>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 p-6 overflow-y-auto space-y-4" id="chat-messages-box" style="scroll-behavior: smooth;">
            @foreach($messages as $msg)
                @if($msg['sender'] === 'bot')
                    <!-- Bot Message -->
                    <div class="flex items-start gap-3 max-w-[80%]">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                            🤖
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-2xl rounded-tl-none px-4 py-3 text-sm shadow-sm leading-relaxed whitespace-pre-wrap">
                            {!! \Illuminate\Support\Str::markdown($msg['text']) !!}
                            <span class="block text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 text-right font-medium">
                                {{ $msg['time'] }}
                            </span>
                        </div>
                    </div>
                @else
                    <!-- User Message -->
                    <div class="flex items-start gap-3 max-w-[80%] ml-auto flex-row-reverse">
                        <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-sm font-semibold text-white flex-shrink-0">
                            👤
                        </div>
                        <div class="bg-indigo-600 text-white rounded-2xl rounded-tr-none px-4 py-3 text-sm shadow-sm leading-relaxed whitespace-pre-wrap">
                            {{ $msg['text'] }}
                            <span class="block text-[10px] text-indigo-200 mt-1.5 text-right font-medium">
                                {{ $msg['time'] }}
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Quick Replies / Suggestions -->
        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-950/40 border-t border-gray-100 dark:border-gray-800/60 overflow-x-auto whitespace-nowrap flex gap-2 no-scrollbar" style="-ms-overflow-style: none; scrollbar-width: none;">
            @foreach($quickReplies as $reply)
                <button 
                    type="button"
                    wire:click="sendMessage('{{ addslashes($reply) }}')"
                    class="inline-block px-3.5 py-1.5 bg-white dark:bg-gray-800 text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-800 transition duration-150 cursor-pointer shadow-sm"
                >
                    {{ $reply }}
                </button>
            @endforeach
        </div>

        <!-- Chat Input Form -->
        <form wire:submit.prevent="sendMessage" class="px-6 py-4 bg-gray-50 dark:bg-gray-950/60 border-t border-gray-200 dark:border-gray-800 flex gap-3 items-center">
            <input 
                type="text" 
                wire:model="userInput" 
                placeholder="Tulis pertanyaan Anda di sini..." 
                class="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150"
            />
            <button 
                type="submit" 
                class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl px-5 py-3 text-sm font-semibold flex items-center justify-center transition duration-150 shadow-sm cursor-pointer"
            >
                Kirim
            </button>
        </form>
    </div>

    <!-- Script to Auto Scroll Message Box to Bottom -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scrollBox = document.getElementById('chat-messages-box');
            if (scrollBox) {
                scrollBox.scrollTop = scrollBox.scrollHeight;
            }

            // Listen for Livewire updates to auto scroll
            Livewire.hook('morph.updated', ({ component }) => {
                if (component.name === 'App\\Filament\\Pages\\Mahasiswa\\ChatbotFAQ') {
                    const box = document.getElementById('chat-messages-box');
                    if (box) {
                        setTimeout(() => {
                            box.scrollTop = box.scrollHeight;
                        }, 50);
                    }
                }
            });
        });
    </script>
</x-filament-panels::page>
