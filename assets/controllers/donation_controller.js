import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["list", "item", "empty", "modal"];
}
