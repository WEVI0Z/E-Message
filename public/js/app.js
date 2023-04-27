const LOCAL_HREF = "http://0.0.0.0/";
const API_HREF = LOCAL_HREF + "api/";

if(document.querySelector(".login__form")) {
    console.log("Login");
    login();
} else if(document.querySelector(".register__form")) {
    console.log("Register")
    register();
} else if(document.querySelector("main")) {
    console.log("Main");
    main();
}

function getToken() {
    return localStorage.getItem("access_token");
}

function setToken(token) {
    return localStorage.setItem("access_token", token)
}

function main() {
    function logout() {
        localStorage.removeItem("access_token");

        window.location.href = LOCAL_HREF + "login";
    }

    if(getToken()) {
        const logoutButton = document.querySelector(".header .navigation__item.user");

        function logoutButtonPressHandler(event) {
            event.preventDefault();

            logout();
        }
        
        function addHandlers() {
            logoutButton.addEventListener("click", logoutButtonPressHandler);
        }

        function removeHandlers() {
            logoutButton.addEventListener("click", logoutButtonPressHandler);
        }

        addHandlers();
    } else {
        window.location.href = LOCAL_HREF + "login";
    }
}

function login() {
    const form = document.querySelector(".login__form");
    const submit = form.querySelector(".form__submit");

    const fields = {
        "login": form.querySelector(".form__input--login"),
        "password": form.querySelector(".form__input--password")
    }

    async function submitPressHandler(event) {
        event.preventDefault();

        const body = {
            "login": fields.login.value,
            "password": fields.password.value,
        }

        const response = await fetch(API_HREF + "login", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(body)
        })

        if(response.ok) {
            const result = await response.json();
            
            const token = result.access_token;
            
            localStorage.setItem("access_token", token);

            window.location.href = LOCAL_HREF;

            removeHandlers();
        } else {
            const paragraph = form.querySelector(".form__error");

            paragraph.classList.remove("hidden");
        }
    }

    function addHandlers() {
        submit.addEventListener("click", submitPressHandler);
    }

    function removeHandlers() {
        submit.removeEventListener("click", submitPressHandler);
    }

    addHandlers();
}

function register() {
    const form = document.querySelector(".register__form");
    const submit = form.querySelector(".form__submit");

    const fields = {
        "login": form.querySelector(".form__input--login"),
        "password": form.querySelector(".form__input--password"),
        "passwordRepeat": form.querySelector(".form__input--password-repeat")
    }

    async function submitPressHandler(event) {
        event.preventDefault();

        const infoWindow = form.querySelector(".form__error");

        infoWindow.textContent = "";

        const body = {
            "login": fields.login.value,
            "password": fields.password.value,
            "passwordRepeat": fields.passwordRepeat.value,
        }

        if(body.password !== body.passwordRepeat) {
            infoWindow.classList.remove("hidden");
            infoWindow.textContent = "Пароли должны совпадать";
        } else {
            const response = await fetch(API_HREF + "register", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(body)
            })
    
            if(response.ok) {
                const result = await response.json();
                
                const token = result.access_token;
                
                localStorage.setItem("access_token", token);
    
                window.location.href = LOCAL_HREF;
    
                removeHandlers();
            } else if(response.status === 400){
                const paragraph = form.querySelector(".form__error");
    
                const result = await response.json();

                const errors = result.errors;

                if(errors.login) {
                    paragraph.textContent += errors.login + " "
                }

                if(errors.password) {
                    paragraph.textContent += errors.password + " "
                }

                paragraph.classList.remove("hidden");
            } else {

            }
        }
    }

    function addHandlers() {
        submit.addEventListener("click", submitPressHandler);
    }

    function removeHandlers() {
        submit.removeEventListener("click", submitPressHandler);
    }

    addHandlers();
}