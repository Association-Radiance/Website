import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["dialog", "dialogContent"];

  async new(event) {
    const url = event.currentTarget.dataset.donationNewUrlValue;

    this.openDialog(url);
  }

  async openDialog(url) {
    this.dialogTarget.showModal();

    try {
      const response = await fetch(url);

      const html = await response.text();

      console.log(html);

      this.dialogContentTarget.innerHTML = html;
    } catch (error) {
      console.log(error);
    }
  }

  closeOnBackdrop(event) {
    if (event.target === this.dialogTarget) {
      this.dialogTarget.close();
    }
  }
}
