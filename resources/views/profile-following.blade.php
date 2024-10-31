<x-profile :username="$username" :currently-following="$currentlyFollowing" :posts="$posts" doctitle="Who follows {{ $username->username }}">
    @include('profile-following-only')
</x-profile>
