<div class="messanger">
    <ul class="messanger__messages">
        @foreach ([1, 2, 3, 4, 5] as $message)
            @include('messanger.message')
        @endforeach
    </ul>

    <form class="messanger__form" action="post-message" method="post">
        <input type="text" name="text" id="text" placeholder="Write a message...">
        <div class="form__down-part">
            <button type="submit">Send</button>
        </div>
    </form>
</div>