@extends('layouts.layout')

@section('content')
    <section class="authorization">
        <form action="" method="POST" class="login__form form">
            <p class="form__error hidden">
                Неправильный логин или пароль
            </p>
            <label for="login" class="form__label">
                Логин:
                <input type="text" name="login" id="login" class="form__input form__input--login" placeholder="username...">
            </label>

            <label for="password" class="form__label">
                Пароль:
                <input type="password" name="password" id="password" class="form__input form__input--password" placeholder="pass123...">
            </label>

            <button class="form__submit" type="submit">Войти</button>
            
            <a class="form__suggest" href="{{route('register')}}">Регистрация</a>

            @csrf
        </form>
    </section>
@endsection