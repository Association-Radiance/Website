import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = { total: Number };

    static targets = ["goal", "progress", "indicator", "percentage", "border", "borderProgress"];

    connect() {
        this.displayGoalsProgress();
    }

    displayGoalsProgress() {
        let previousTarget = 0;

        this.goalTargets.forEach((goal) => {
            const target = Number(goal.dataset.amount);

            const indicator = goal.querySelector('[data-donation-goal-target="indicator"]');
            const percentageElement = goal.querySelector('[data-donation-goal-target="percentage"]');

            if (!indicator || !percentageElement) {
                previousTarget = target;
                return;
            }

            const goalAmount = Math.max(0, Math.min(this.totalValue, target) - previousTarget);

            const goalSize = target - previousTarget;

            const progress = Math.min((goalAmount / goalSize) * 100, 100);

            this.borderTarget.style.width = `calc(${progress}% + 1px)`;
            this.borderProgressTarget.style.width = `calc(${100 - progress}% + 1px)`;

            if (progress === 0) {
                this.borderTarget.style.width = 0;
                this.borderProgressTarget.style.width = "calc(100% + 2px)";
                this.borderProgressTarget.style.borderRadius = "8px";
            }

            indicator.style.width = `${progress}%`;
            percentageElement.textContent = `${progress.toFixed(2)}%`;

            previousTarget = target;
        });
    }
}
