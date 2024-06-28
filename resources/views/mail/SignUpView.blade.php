@component('mail::message')
    <div style="display: flex; justify-content: center; flex-direction: column;">
        <div style="display: flex; justify-content: center; flex-direction: column; align-items: center">
            <h1>Здравствуйте {{$name}}</h1>
            <h3>Ваш код подтверждения</h3>
        </div>
        <x-mail.verify-code :$verifyCode/>
    </div>
    @component('mail::button', ['url' => 'https://google.com'])
        Верификация
    @endcomponent
@endcomponent
