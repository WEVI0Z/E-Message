<div class="side-panel">
    <div class="input__container">
        <input class="side-panel__search" type="search" name="search" id="search" placeholder="Search">
    </div>

    <ul class="previews">
    </ul>
    @foreach ([1, 2, 3, 4, 5] as $preview)
        <li>
            @include("side-panel.preview")
        </li>
    @endforeach
</div>