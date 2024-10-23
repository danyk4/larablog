<x-profile :username="$username" :currently-following="$currentlyFollowing" :posts="$posts" doctitle="{{ $username->username }} profile page">
    <div class="list-group">
        @foreach($posts as $post)
            <x-posts :post="$post" hideAuthor/>
        @endforeach
    </div>
</x-profile>
