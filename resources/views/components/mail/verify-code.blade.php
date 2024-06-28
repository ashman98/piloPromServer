<div class="container">
    <div class="code-container">
        @foreach ($verifyCode as $int)
            <span class="code" >{{ $int }}</span>
        @endforeach
    </div>
    <small class="info">
        Это только для оформления. На самом деле мы не отправляли вам электронное письмо, так как у нас нет вашего электронного адреса, верно?
    </small>
</div>
