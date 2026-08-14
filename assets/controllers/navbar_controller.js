import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["menu", "button"];
    static classes = ["open"];

    connect() {
        this.closeOnEscape = this.closeOnEscape.bind(this);
        this.closeOnClickOutside = this.closeOnClickOutside.bind(this);
        this.closeOnScroll = this.closeOnScroll.bind(this);

        document.addEventListener("keydown", this.closeOnEscape);
        document.addEventListener("click", this.closeOnClickOutside);
        window.addEventListener("scroll", this.closeOnScroll, { passive: true });
    }

    disconnect() {
        document.removeEventListener("keydown", this.closeOnEscape);
        document.removeEventListener("click", this.closeOnClickOutside);
        window.removeEventListener("scroll", this.closeOnScroll);
    }

    toggle() {
        const isOpen = this.menuTarget.classList.toggle(this.openClass);

        this.buttonTarget.setAttribute("aria-expanded", String(isOpen));
        this.buttonTarget.setAttribute("aria-label", isOpen ? "Fermer le menu" : "Ouvrir le menu");
    }

    close() {
        this.menuTarget.classList.remove(this.openClass);
        this.buttonTarget.setAttribute("aria-expanded", "false");
        this.buttonTarget.setAttribute("aria-label", "Ouvrir le menu");
    }

    closeOnEscape(event) {
        if (event.key === "Escape") {
            this.close();
        }
    }

    closeOnClickOutside(event) {
        if (!this.element.contains(event.target)) {
            this.close();
        }
    }

    closeOnScroll() {
        this.close();
    }
}
