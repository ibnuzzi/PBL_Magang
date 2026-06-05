<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="signaturePad(@entangle($getStatePath()))"
         x-init="init()"
         class="flex flex-col gap-2"
    >
        <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-900"
             style="max-width: 400px; height: 200px; position: relative;"
        >
            <canvas x-ref="canvas" 
                    class="w-full h-full cursor-crosshair"
                    style="display: block; width: 100%; height: 100%;"
            ></canvas>
        </div>
        
        <div class="flex gap-2">
            <button type="button" 
                    x-on:click="clear()" 
                    class="px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700"
            >
                Hapus Tanda Tangan
            </button>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            if (window.signaturePadInitialized) return;
            window.signaturePadInitialized = true;
            
            Alpine.data('signaturePad', (state) => ({
                state: state,
                canvas: null,
                ctx: null,
                drawing: false,
                
                init() {
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d');
                    
                    const resize = () => {
                        const rect = this.canvas.getBoundingClientRect();
                        this.canvas.width = rect.width;
                        this.canvas.height = rect.height;
                        
                        if (this.state && typeof this.state === 'string') {
                            const img = new Image();
                            img.onload = () => {
                                this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
                            };
                            img.src = this.state.startsWith('data:image/png;base64,') 
                                ? this.state 
                                : '/storage/' + this.state;
                        }
                    };
                    
                    setTimeout(resize, 100);
                    window.addEventListener('resize', resize);
                    
                    const getPos = (e) => {
                        const rect = this.canvas.getBoundingClientRect();
                        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                        return {
                            x: clientX - rect.left,
                            y: clientY - rect.top
                        };
                    };
                    
                    const startDraw = (e) => {
                        e.preventDefault();
                        this.drawing = true;
                        const pos = getPos(e);
                        this.ctx.beginPath();
                        this.ctx.moveTo(pos.x, pos.y);
                    };
                    
                    const draw = (e) => {
                        if (!this.drawing) return;
                        e.preventDefault();
                        const pos = getPos(e);
                        this.ctx.lineTo(pos.x, pos.y);
                        this.ctx.strokeStyle = '#000000';
                        this.ctx.lineWidth = 2.5;
                        this.ctx.lineCap = 'round';
                        this.ctx.stroke();
                    };
                    
                    const stopDraw = () => {
                        if (!this.drawing) return;
                        this.drawing = false;
                        this.save();
                    };
                    
                    this.canvas.addEventListener('mousedown', startDraw);
                    this.canvas.addEventListener('mousemove', draw);
                    window.addEventListener('mouseup', stopDraw);
                    
                    this.canvas.addEventListener('touchstart', startDraw, { passive: false });
                    this.canvas.addEventListener('touchmove', draw, { passive: false });
                    window.addEventListener('touchend', stopDraw);
                },
                
                clear() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    this.state = null;
                },
                
                save() {
                    const blank = document.createElement('canvas');
                    blank.width = this.canvas.width;
                    blank.height = this.canvas.height;
                    if (this.canvas.toDataURL() === blank.toDataURL()) {
                        this.state = null;
                    } else {
                        this.state = this.canvas.toDataURL();
                    }
                }
            }));
        });
    </script>
</x-dynamic-component>
