<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('title', $post->title) }}">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail (Optional)</label>
                        <div class="mt-2 flex items-center gap-4">
                            <!-- Current or New Preview -->
                            <div id="image-preview" class="{{ $post->thumbnail ? '' : 'hidden' }} w-32 h-32 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden relative">
                                <img src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : '' }}" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            
                            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                        </div>
                        @error('thumbnail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <script>
                        document.getElementById('thumbnail').addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            const preview = document.getElementById('image-preview');
                            const img = preview.querySelector('img');

                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    img.src = e.target.result;
                                    preview.classList.remove('hidden');
                                }
                                reader.readAsDataURL(file);
                            }
                        });
                    </script>

                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" id="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('content', $post->content) }}</textarea>
                        @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- TinyMCE -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
                    <script>
                        tinymce.init({
                            selector: '#content',
                            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                        });
                    </script>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">Publish immediately</label>
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_locked" id="is_locked" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('is_locked', $post->is_locked) ? 'checked' : '' }}>
                        <label for="is_locked" class="ml-2 block text-sm text-gray-900">Lock Content (Unlock later to notify users)</label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
