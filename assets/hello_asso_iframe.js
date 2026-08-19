window.addEventListener("message", function (e) {
    if (!e.origin.includes("helloasso")) {
        return;
    }

    const iframe = document.getElementById("haWidgetLight");

    if (!iframe) {
        return;
    }

    const height = parseFloat(e.data?.height);

    if (!isNaN(height)) {
        iframe.style.height = `${height}px`;
    }
});
