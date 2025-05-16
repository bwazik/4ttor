/**
 *
 * Pricing
 */
"use strict";

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        const priceDurationTogglers = document.querySelectorAll(
                'input[name="inlineRadioOptions"]'
            ),
            priceMonthlyList = [].slice.call(
                document.querySelectorAll(".price-monthly")
            ),
            priceTermList = [].slice.call(
                document.querySelectorAll(".price-term")
            ),
            priceYearlyList = [].slice.call(
                document.querySelectorAll(".price-yearly")
            ),
            priceTermToggleList = [].slice.call(
                document.querySelectorAll(".price-term-toggle")
            ),
            priceYearlyToggleList = [].slice.call(
                document.querySelectorAll(".price-yearly-toggle")
            ),
            planDurations = [].slice.call(
                document.querySelectorAll(".plan-duration")
            );

        function togglePrice() {
            const value = this.value;
            // Update form duration input
            planDurations.forEach((input) => (input.value = value));

            if (value === "monthly") {
                priceMonthlyList.forEach((el) => el.classList.remove("d-none"));
                priceTermList.forEach((el) => el.classList.add("d-none"));
                priceYearlyList.forEach((el) => el.classList.add("d-none"));
                priceTermToggleList.forEach((el) => el.classList.add("d-none"));
                priceYearlyToggleList.forEach((el) =>
                    el.classList.add("d-none")
                );
            } else if (value === "term") {
                priceMonthlyList.forEach((el) => el.classList.add("d-none"));
                priceTermList.forEach((el) => el.classList.remove("d-none"));
                priceYearlyList.forEach((el) => el.classList.add("d-none"));
                priceTermToggleList.forEach((el) =>
                    el.classList.remove("d-none")
                );
                priceYearlyToggleList.forEach((el) =>
                    el.classList.add("d-none")
                );
            } else if (value === "yearly") {
                priceMonthlyList.forEach((el) => el.classList.add("d-none"));
                priceTermList.forEach((el) => el.classList.add("d-none"));
                priceYearlyList.forEach((el) => el.classList.remove("d-none"));
                priceTermToggleList.forEach((el) => el.classList.add("d-none"));
                priceYearlyToggleList.forEach((el) =>
                    el.classList.remove("d-none")
                );
            }
        }

        // Set default to Monthly
        const monthlyRadio = document.querySelector("#inlineRadio1");
        if (monthlyRadio) {
            monthlyRadio.checked = true;
            togglePrice.call(monthlyRadio);
        }

        // Add event listeners to all radio buttons
        priceDurationTogglers.forEach((toggler) => {
            toggler.addEventListener("change", togglePrice);
        });
    })();
});
