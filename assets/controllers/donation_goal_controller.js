import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = { total: Number };

    static targets = ["goal", "progress", "border", "amount"];

    connect() {
        this.displayGoalsProgress();
    }

    displayGoalsProgress() {
        let previousTarget = 0;

        this.goalTargets.forEach((goal) => {
            const target = Number(goal.dataset.amount);

            const progress = goal.querySelector('[data-donation-goal-target="progress"]');
            const border = goal.querySelector('[data-donation-goal-target="border"]');
            const amount = goal.querySelector('[data-donation-goal-target="amount"]');

            if (!amount) {
                previousTarget = target;

                border.style.width = 0;
                progress.style.width = "calc(100% + 2px)";
                progress.style.borderRadius = "8px";

                return;
            }

            const goalAmount = Math.max(0, Math.min(this.totalValue, target) - previousTarget);

            const goalSize = target - previousTarget;

            const percentage = Math.round(Math.min((goalAmount / goalSize) * 100, 100));

            amount.textContent = Math.max(0, Math.min(this.totalValue, target)) / 100 + " €";

            progress.style.width = `calc(${percentage}% + 1px)`;
            border.style.width = `calc(${100 - percentage}% + 1px)`;

            if (percentage === 0) {
                progress.style.width = 0;
                border.style.width = "calc(100% + 2px)";
                border.style.borderRadius = "8px";
            }

            if (percentage === 100) {
                border.style.width = 0;
                progress.style.width = "calc(100% + 2px)";
                progress.style.borderRadius = "8px";
            }

            previousTarget = target;
        });
    }
}
