<x-profile :username="$username" :currently-following="$currentlyFollowing" :posts="$posts" doctitle="{{ $username->username }} profile page">
    @include('profile-posts-only')
</x-profile>
