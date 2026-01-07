<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Blog Posts') }}
            </h2>
            <a href="{{ route('admin.posts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                New Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Title</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Published At</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $post->title }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($post->is_published)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Published</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block delete-confirm-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($posts->isEmpty())
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center">No posts found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
