<div class="list-group">
    @foreach($followers as $follow)
        <a href="/profile/{{ $follow->userDoingFollowing->username }}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{ $follow->userDoingFollowing->avatar }}"/>
            {{ $follow->userDoingFollowing->username }}
        </a>
    @endforeach
</div>

