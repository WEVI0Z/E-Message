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
        const searchInput = document.querySelector(".side-panel__search");
        const previews = document.querySelector(".previews");

        function logoutButtonPressHandler(event) {
            event.preventDefault();

            removeHandlers();

            logout();
        }

        async function searchInputChangeHandler(event) {
            event.preventDefault();
            previews.innerHTML = "";

            const value = searchInput.value;

            const response = await fetch(API_HREF + "users/search", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'token': localStorage.getItem("access_token")
                },
                body: JSON.stringify({text: value})
            })
            
            const users = await response.json();

            users.forEach(user => {
                previews.innerHTML += `
                    <a href="#" class="preview">
                        <img class="preview__avatar" src="${LOCAL_HREF}images/avatar-1.png" width="40" height="40" alt="user avatar">
                        <div class="content-part">
                            <div class="top-part">
                                <h3 class="preview__name">${user.login}</h3>
                            </div>
                        </div>
                    </a>
                `
            });
        }
        
        function addHandlers() {
            logoutButton.addEventListener("click", logoutButtonPressHandler);
            searchInput.addEventListener("change", searchInputChangeHandler);
        }

        function removeHandlers() {
            logoutButton.removeEventListener("click", logoutButtonPressHandler);
            searchInput.removeEventListener("change", searchInputChangeHandler);
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