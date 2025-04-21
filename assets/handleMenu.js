window.addEventListener('DOMContentLoaded', function () {
    let menuButton = document.getElementById('menu-button');
    let menu = document.getElementById('menu');
    let mainContainer = document.getElementById('main');

    let menuButtonState = false

    document.addEventListener('click', function (event) {
        if (!event.target.contains(menuButton)) {
            menuButtonState = false
        } else {
            menuButtonState = !menuButtonState;
        }

        menuButtonState ? menu.classList.add("active") : menu.classList.remove("active");
    })

    mainContainer.addEventListener('scroll', function () {
        menuButtonState = false;
        menu.classList.remove("active");
    })
})
