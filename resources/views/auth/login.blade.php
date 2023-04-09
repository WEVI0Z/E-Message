@extends('layouts.layout')

@section('content')
    <section class="authorization">
        <form action="POST" class="login__form form">
            <label for="login" class="form__label">
                Логин:
                <input type="text" name="login" id="login" class="form__input" placeholder="username...">
            </label>

            <label for="password" class="form__label">
                Пароль:
                <input type="password" name="password" id="password" class="form__input" placeholder="pass123...">
            </label>

            <button class="form__submit" type="submit">Войти</button>
            
            <a class="form__suggest" href="{{route('register')}}">Регистрация</a>
        </form>
    </section>
@endsection