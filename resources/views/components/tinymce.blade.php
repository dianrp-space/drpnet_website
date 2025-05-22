@props(['id' => 'content', 'name' => 'content', 'value' => ''])
<textarea name="{{ $name }}" id="{{ $id }}" rows="12"
    {{ $attributes->merge(['class' => 'w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600']) }}>{{ $value }}</textarea>
@once
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('tinymce.api_key') }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#{{ $id }}',
                @foreach(config('tinymce.default_options') as $option => $value)
                    {{ $option }}: @json($value),
                @endforeach
                {{ $attributes->get('options') ?? '' }}
                setup: function (editor) {
                    // Sinkronisasi konten TinyMCE ke textarea sebelum submit
                    editor.on('change', function () {
                        editor.save();
                    });
                    
                    // Pastikan konten tersinkronisasi saat form akan disubmit
                    editor.on('submit', function () {
                        editor.save();
                    });
                }
            });
            
            // Tambahkan event listener untuk form submission
            document.addEventListener('submit', function(e) {
                // Pastikan semua instance TinyMCE menyimpan kontennya
                tinymce.activeEditor && tinymce.activeEditor.save();
                
                // Atau untuk semua editor jika ada multiple instance
                tinymce.editors.forEach(function(editor) {
                    editor.save();
                });
            });
        });
    </script>
    @endpush
@endonce