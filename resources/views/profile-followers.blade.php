<x-profile
    :username="$username"
    :currently-following="$currentlyFollowing"
    :posts="$posts"
    doctitle="{{ $username->username }} followers"
>
    @include('profile-followers-only')
</x-profile>
