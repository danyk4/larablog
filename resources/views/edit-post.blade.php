<x-layout>
    <div class="container py-md-5 container--narrow">
        <form action="/post/{{ $post->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
                <input required name="title" value="{{ old('title', $post->title) }}" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
                @error('title')
                    <p class="text-danger small m-0">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
                <textarea required name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{ old('body', $post->body) }}</textarea>
                @error('body')
                <p class="text-danger small m-0">{{ $message }}</p>
                @enderror
            </div>

            <button class="btn btn-primary mt-2">Save Changes</button>
            <a href="/post/{{ $post->id }}" class="btn btn-secondary mt-2">Cancel</a>
        </form>
    </div>
</x-layout>

